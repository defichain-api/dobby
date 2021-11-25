<?php

namespace App\Notifications;

use App\Channel\WebhookChannel;
use App\Enum\CooldownTypes;
use App\Enum\NotificationGatewayType;
use App\Enum\QueueName;
use App\Models\NotificationTrigger;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;

class BaseUserNotification extends Notification
{
	public function __construct(protected Vault $vault)
	{
	}

	public function via(User $user): array
	{
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
