<?php

namespace App\Http\BotmanConversation;

use App\Api\Service\NotificationGatewayService;
use App\Enum\NotificationGatewayType;
use App\Models\NotificationGateway;
use App\Models\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Str;

class SetupConversation extends Conversation
{
	protected TelegramMessageService $telegramMessageService;
	protected NotificationGatewayService $gatewayService;

	public function __construct()
	{
		$this->telegramMessageService = app(TelegramMessageService::class);
		$this->gatewayService = app(NotificationGatewayService::class);
	}

	/**
	 * @inheritDoc
	 * @throws \BotMan\BotMan\Exceptions\Base\BotManException
	 */
	public function run(): void
	{
		/** @var \BotMan\BotMan\Messages\Incoming\IncomingMessage $message */
		$message = $this->getBot()->getMessages()[0];
		$senderId = $message->getSender();
		$userId = trim(Str::replace('/start', '', $message->getText()));

		$alreadyRegisteredTelegramId = NotificationGateway::whereType(NotificationGatewayType::TELEGRAM)
			->whereValue($this->bot->getUser()->getId())
			->where('userId', $userId)
			->count();

		if ($alreadyRegisteredTelegramId > 0) {
			$this->telegramMessageService->send(__('bot/setup.already_registered'), $senderId);
		} elseif ($userId === '') {
			// default state: clicking on /start
			$this->telegramMessageService->send(__('bot/setup.enter_user_key', ['url' => config('app.url')]),
				$senderId);
		} elseif (!Str::isUuid($userId)
			|| User::where('id', $userId)->count() === 0) {
			// not registered yet
			$this->telegramMessageService->send(__('bot/setup.not_registered', ['url' => config('app.url')]),
				$senderId);
		} elseif ($this->gatewayService->hasGatewayWithValue($senderId, NotificationGatewayType::TELEGRAM)) {
			// reconnect telegram to new user key
			$this->gatewayService->createOrUpdateTelegramGateway($userId, $senderId);
			$this->telegramMessageService->send(__('bot/setup.telegram_reconnect'), $senderId);
		} else {
			// start registering the user
			$this->telegramMessageService->send(__('bot/setup.registering.collect_data'), $senderId);
			$this->gatewayService->createOrUpdateTelegramGateway($userId, $senderId);
			$this->bot->typesAndWaits(2);
			$this->telegramMessageService->send(__('bot/setup.registering.setup_finished',
				['url' => config('app.url')]), $senderId);
		}
	}
}
