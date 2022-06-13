<?php

namespace App\Http\BotmanConversation;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\TelegramDriver;

class TelegramMessageService
{
	protected BotMan $botman;

	public function __construct()
	{
		$this->botman = app('botman');
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

	public function sendWithButton(string $message, string $recipient, string $question, string $url): void
	{
		$question = Question::create($message)
			->addButton(Button::create($question)->additionalParameters(['url' => $url]));

		$this->botman->ask($question, function () {
		}, [
			'parse_mode'               => 'Markdown',
			'disable_web_page_preview' => true,
		], $recipient, TelegramDriver::class);
	}
}
