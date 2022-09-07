<?php

namespace App\Http\BotmanConversation;

use App\Api\Service\VaultRepository;
use App\Enum\NotificationGatewayType;
use App\Models\NotificationGateway;
use BotMan\BotMan\Messages\Conversations\Conversation;

class StateConversation extends Conversation
{
	public function run(): void
	{
		$user = NotificationGateway::where('type', NotificationGatewayType::TELEGRAM)
			->where('value', $this->bot->getUser()->getId())
			->first()?->user;

		if (is_null($user)) {
			$this->say(__('bot/setup.unknown_user', ['url' => config('app.frontend_url')]), ['parse_mode' => 'Markdown']);

			return;
		}

		$this->say(__('notifications/telegram/current_summary.intro') . "\r\n\r\n###############################\r\n\r\n",
			['parse_mode' => 'Markdown']);
		$message = '';
		foreach (app(VaultRepository::class)->vaultsDataForUser($user) as $vault) {
			$message .= __('notifications/telegram/current_summary.vault_details',
					$vault) . "\r\n\r\n###############################\r\n\r\n";
		}

		$this->say($message, ['parse_mode' => 'Markdown']);
	}
}
