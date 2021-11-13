<?php

namespace App\Http\Controllers;

use App\Http\BotmanConversation\SetupConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Base\BotManException;
use Throwable;

class BotController
{
	public function handle(): void
	{
		/** @var Botman $botMan */
		$botMan = app('botman');

		$botMan->startConversation(new SetupConversation());

		$botMan->exception(BotManException::class, function (Throwable $throwable, $bot) {
			$bot->reply('An error occurred. Try again later...');
		});
		$botMan->listen();
	}
}
