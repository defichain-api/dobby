<?php

namespace App\Http\BotmanConversation;

use App\Enum\NotificationGatewayType;
use App\Models\NotificationGateway;
use BotMan\BotMan\Messages\Conversations\Conversation;

class UserKeyConversation extends Conversation
{
	public function run(): void
	{
		$user = NotificationGateway::where('type', NotificationGatewayType::TELEGRAM)
			->where('value', $this->bot->getUser()->getId())
			->first()?->user;

		if (is_null($user)) {
			$this->say(__('bot/setup.unknown_user', ['url' => config('app.url')]), ['parse_mode' => 'Markdown']);

			return;
		}

		$this->say(__('bot/user_key.user_key'), ['parse_mode' => 'Markdown']);
		$this->say(sprintf('<span class="tg-spoiler"><b>%s</b></span>', $user->id), ['parse_mode' => 'HTML']);
	}
}
