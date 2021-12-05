<?php

namespace App\Notifications;

use App\Channel\WebhookChannel;
use App\Enum\CooldownTypes;
use App\Enum\NotificationGatewayType;
use App\Models\NotificationTrigger;
use App\Models\Vault;

class BaseTriggerNotification extends BaseNotification
{
	public function __construct(protected Vault $vault)
	{
		parent::__construct();
	}

	public function via(NotificationTrigger $notifiable): array
	{
		$methods = [];
		if ($notifiable->hasGateway(NotificationGatewayType::TELEGRAM)
			&& $notifiable->cooldown(CooldownTypes::TELEGRAM_NOTIFICATION)->passed()) {
			$methods[] = NotificationGatewayType::TELEGRAM;
		}
		if ($notifiable->hasGateway(NotificationGatewayType::WEBHOOK)) {
			$methods[] = WebhookChannel::class;
		}
		if ($notifiable->hasGateway(NotificationGatewayType::MAIL)
			&& $notifiable->cooldown(CooldownTypes::MAIL_NOTIFICATION)->passed()) {
			$methods[] = NotificationGatewayType::MAIL;
		}

		return $methods;
	}
}
