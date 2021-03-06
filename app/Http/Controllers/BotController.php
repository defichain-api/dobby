<?php

namespace App\Http\Controllers;

use App\Api\Service\NotificationGatewayService;
use App\Http\BotmanConversation\DisableGatewayConversation;
use App\Http\BotmanConversation\RemoveSnoozeConversation;
use App\Http\BotmanConversation\SetupConversation;
use App\Http\BotmanConversation\SnoozeConversation;
use App\Http\BotmanConversation\StateConversation;
use App\Http\BotmanConversation\UserKeyConversation;
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
		} catch (Exception) {
			return;
		}

		$botMan->hears('snooze_([0-9]+_[0-9]+)', function (BotMan $botMan) use ($message) {
			$botMan->startConversation(new SnoozeConversation($message));
		});
		$botMan->hears('/vault_state', function (BotMan $botMan) {
			$botMan->startConversation(new StateConversation());
		});
		$botMan->hears('/user_key', function (BotMan $botMan) {
			$botMan->startConversation(new UserKeyConversation());
		});
		$botMan->hears('/remove_snooze', function (BotMan $botMan) {
			$botMan->startConversation(new RemoveSnoozeConversation());
		});
		$botMan->hears('/disable_telegram', function (BotMan $botMan) {
			$botMan->startConversation(new DisableGatewayConversation(app(NotificationGatewayService::class)));
		});
		$botMan->fallback(function (Botman $botMan) {
			$botMan->startConversation(new SetupConversation());
		});

		$botMan->exception(BotManException::class, function (Throwable $throwable, $bot) {
			$bot->reply('An error occurred. Try again later...');
		});
		$botMan->listen();
	}
}
