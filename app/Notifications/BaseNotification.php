<?php

namespace App\Notifications;

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

	public function __construct()
	{
		$this->statisticService = app(StatisticService::class);
	}

	#[ArrayShape(['telegram' => "string", 'mail' => "string", 'webhook' => "string"])]
	public function viaQueues(): array
	{
		return [
			'telegram' => QueueName::NOTIFICATION_TELEGRAM_QUEUE,
			'mail'     => QueueName::NOTIFICATION_EMAIL_QUEUE,
			'webhook'  => QueueName::NOTIFICATION_WEBHOOK_QUEUE,
		];
	}

	protected function snooze(User|NotificationTrigger $model, string $type, Carbon $until): void
	{
		$model->cooldown($type)->until($until);
	}
}
