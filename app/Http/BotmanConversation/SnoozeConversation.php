<?php

namespace App\Http\BotmanConversation;

use App\Enum\CooldownTypes;
use App\Models\NotificationTrigger;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Str;

class SnoozeConversation extends Conversation
{
	protected TelegramMessageService $telegramMessageService;
	protected int $cooldownMinutes;
	protected int $triggerId;

	public function __construct(string $message)
	{
		$this->telegramMessageService = app(TelegramMessageService::class);

		/**
		 * message has the format:
		 * "snooze_TRIGGER-ID_COOLDOWNTIME-MINUTES" - id and cooldown are represented as int
		 */
		$message = Str::replace('snooze_', '', $message);
		$segments = Str::of($message)->split('/[\s_]+/');
		$this->cooldownMinutes = $segments->pop() ?? 0;
		$this->triggerId = $segments->pop() ?? -1;
	}

	/**
	 * @inheritDoc
	 * @throws \BotMan\BotMan\Exceptions\Base\BotManException
	 */
	public function run(): void
	{
		$trigger = NotificationTrigger::find($this->triggerId);
		if (is_null($trigger)) {
			return;
		}
		$trigger->cooldown(CooldownTypes::TELEGRAM_NOTIFICATION)
			->until(now()->addMinutes($this->cooldownMinutes));

		$this->telegramMessageService->send(
			trans_choice('bot/snooze.snooze', $this->cooldownMinutes, ['time' => $this->cooldownMinutes / 60]),
			$this->getBot()->getMessages()[0]->getSender()
		);
	}
}
