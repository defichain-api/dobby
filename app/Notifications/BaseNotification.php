<?php

namespace App\Notifications;

use App\Enum\NotificationGatewayType;
use App\Enum\QueueName;
use App\Models\NotificationTrigger;
use App\Models\Service\StatisticService;
use App\Models\User;
use Carbon\Carbon;
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
	])]
	public function viaQueues(): array
	{
		return [
			NotificationGatewayType::TELEGRAM => QueueName::NOTIFICATION_TELEGRAM_QUEUE,
			NotificationGatewayType::MAIL     => QueueName::NOTIFICATION_EMAIL_QUEUE,
			NotificationGatewayType::WEBHOOK  => QueueName::NOTIFICATION_WEBHOOK_QUEUE,
		];
	}

	protected function snooze(User|NotificationTrigger $model, string $type, Carbon $until): void
	{
		$model->cooldown($type)->until($until);
	}

//	public function failed(\Exception $e)
//	{
//		if (
//			!($e instanceof CouldNotSendNotification
//				&& \Str::contains($e->getMessage(), 'Forbidden: bot was blocked by the user'))
//		) {
//			return;
//		}
//
//		app(NotificationGatewayRepository::class)->removeTelegram($this->notifiable);
//	}
}
