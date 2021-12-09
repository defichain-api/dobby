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
		ray()->clearAll();
		/** @var Botman $botMan */
		$botMan = app('botman');

		try {
			$message = $botMan->getMessages()[0]->getText();
		} catch (Exception) {
			return;
		}

//		if (\Str::contains($message, 'snooze_')) {
//		}

		$botMan->hears('snooze_([0-9]+_[0-9]+)', function (BotMan $botMan) use ($message) {
			$botMan->startConversation(new SnoozeConversation($message));
			ray('start snooze' . $message);
		});
		$botMan->fallback(function (Botman $botMan) {
//			$botMan->startConversation(new SetupConversation());
		});

		$botMan->exception(BotManException::class, function (Throwable $throwable, $bot) {
			$bot->reply('An error occurred. Try again later...');
		});
		$botMan->listen();
	}
}
