<?php

namespace App\Http\BotmanConversation;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\TelegramDriver;

class TelegramMessageService
{
	protected BotMan $botman;

	public function __construct(BotMan $bot)
	{
		$this->botman = $bot;
	}

	/**
	 * @throws \BotMan\BotMan\Exceptions\Base\BotManException
	 */
	public function send(string $message, string $recipient): void
	{
		$this->botman->say(
			$message,
			$recipient,
			TelegramDriver::class,
			[
				'parse_mode'               => 'Markdown',
				'disable_web_page_preview' => true,
			]
		);
	}
}
