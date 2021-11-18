<?php

namespace App\Http\Controllers;

use App\Http\BotmanConversation\SetupConversation;
use App\Http\BotmanConversation\SnoozeConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Base\BotManException;
use Exception;
use Throwable;

class BotController
{
	public function handle(): void
	{
		/** @var Botman $botMan */
		$botMan = app('botman');

		try {
			$message = $botMan->getMessages()[0]->getText();
		} catch (Exception $e) {
			return;
		}

		if (\Str::contains($message, 'snooze_')) {
			$botMan->startConversation(new SnoozeConversation($message));
		} else {
			$botMan->startConversation(new SetupConversation());
		}

		$botMan->exception(BotManException::class, function (Throwable $throwable, $bot) {
			$bot->reply('An error occurred. Try again later...');
		});
		$botMan->listen();
	}
}
