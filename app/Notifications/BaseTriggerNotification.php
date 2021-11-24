<?php

namespace App\Notifications;

use App\Channel\WebhookChannel;
use App\Enum\CooldownTypes;
use App\Enum\NotificationGatewayType;
use App\Enum\QueueName;
use App\Models\NotificationTrigger;
use App\Models\Vault;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;

class BaseTriggerNotification extends Notification
{
	public function __construct(protected Vault $vault)
	{
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

	#[ArrayShape(['telegram' => "string", 'mail' => "string", WebhookChannel::class => "string"])]
	public function viaQueues(): array
	{
		return [
			'telegram'            => QueueName::NOTIFICATION_TELEGRAM_QUEUE,
			'mail'                => QueueName::NOTIFICATION_EMAIL_QUEUE,
			WebhookChannel::class => QueueName::NOTIFICATION_WEBHOOK_QUEUE,
		];
	}
}
