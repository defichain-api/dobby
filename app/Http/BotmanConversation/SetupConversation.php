<?php

namespace App\Http\BotmanConversation;

use App\Api\Service\NotificationGatewayService;
use App\Enum\NotificationGatewayType;
use App\Models\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\Drivers\Telegram\TelegramDriver;
use Str;

class SetupConversation extends Conversation
{
	protected string $senderId;
	protected string $userId;

	/**
	 * @inheritDoc
	 * @throws \BotMan\BotMan\Exceptions\Base\BotManException
	 */
	public function run(): void
	{
		$gatewayService = app(NotificationGatewayService::class);

		/** @var \BotMan\BotMan\Messages\Incoming\IncomingMessage $message */
		$message = $this->getBot()->getMessages()[0];
		$this->senderId = $message->getSender();
		$this->userId = trim(Str::replace('/start', '', $message->getText()));

		if ($this->userId === '') {
			$this->send(__('bot/setup.enter_user_key', ['url' => config('app.url')]));
			return;
		}

		if (!Str::isUuid($this->userId)
			|| User::where('userId', $this->userId)->count() === 0) {
			// not registered yet
			$this->send(__('bot/setup.not_registered', ['url' => config('app.url')]));

			return;
		}

		if ($gatewayService->hasGatewayForUser($this->userId, NotificationGatewayType::TELEGRAM)) {
			$this->send(__('bot/setup.already_registered'));
		} else {
			$this->send(__('bot/setup.registering.collect_data'));
			$gatewayService->createTelegramGateway($this->userId, $this->senderId);
			$this->bot->typesAndWaits(4);
			$this->send(__('bot/setup.registering.setup_finished', ['url' => config('app.url')]));
		}
	}

	/**
	 * @throws \BotMan\BotMan\Exceptions\Base\BotManException
	 */
	protected function send(string $message): void
	{
		$this->bot->say(
			$message,
			$this->senderId,
			TelegramDriver::class,
			[
				'parse_mode'               => 'Markdown',
				'disable_web_page_preview' => true,
			]
		);
	}
}
