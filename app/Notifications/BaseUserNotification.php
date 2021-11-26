<?php

namespace App\Notifications;

use App\Channel\WebhookChannel;
use App\Enum\CooldownTypes;
use App\Enum\NotificationGatewayType;
use App\Models\User;
use App\Models\Vault;

class BaseUserNotification extends BaseNotification
{
	public function __construct(protected Vault $vault)
	{
	}

	public function via(User $user): array
	{
		ray(get_class($this));

		$methods = [];
		if ($user->hasGateway(NotificationGatewayType::TELEGRAM)
			&& $user->cooldown(CooldownTypes::TELEGRAM_NOTIFICATION)->passed()) {
			$methods[] = NotificationGatewayType::TELEGRAM;
		}
		if ($user->hasGateway(NotificationGatewayType::WEBHOOK)) {
			$methods[] = WebhookChannel::class;
		}
		if ($user->hasGateway(NotificationGatewayType::MAIL)
			&& $user->cooldown(CooldownTypes::MAIL_NOTIFICATION)->passed()) {
			$methods[] = NotificationGatewayType::MAIL;
		}

		return $methods;
	}
}
