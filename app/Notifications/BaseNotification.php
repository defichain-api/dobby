<?php

namespace App\Notifications;

use App\Enum\NotificationGatewayType;
use App\Enum\QueueName;
use App\Models\NotificationTrigger;
use App\Models\Service\StatisticService;
use App\Models\User;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;

class BaseNotification extends Notification
{
	protected StatisticService $statisticService;
	protected User|NotificationTrigger $notifiable;

	public function __construct()
	{
		$this->statisticService = app(StatisticService::class);
	}

	#[ArrayShape([
		NotificationGatewayType::TELEGRAM => "string",
		NotificationGatewayType::MAIL     => "string",
		NotificationGatewayType::WEBHOOK  => "string",
		NotificationGatewayType::PHONE    => "string",
	])]
	public function viaQueues(): array
	{
		return [
			NotificationGatewayType::TELEGRAM => QueueName::NOTIFICATION_TELEGRAM_QUEUE,
			NotificationGatewayType::MAIL     => QueueName::NOTIFICATION_EMAIL_QUEUE,
			NotificationGatewayType::WEBHOOK  => QueueName::NOTIFICATION_WEBHOOK_QUEUE,
			NotificationGatewayType::PHONE    => QueueName::NOTIFICATION_PHONE_QUEUE,
		];
	}
}
