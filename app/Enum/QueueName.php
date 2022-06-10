<?php

namespace App\Enum;

class QueueName
{
	const NOTIFICATION_TELEGRAM_QUEUE = 'notification_telegram';
	const NOTIFICATION_EMAIL_QUEUE = 'notification_email';
	const NOTIFICATION_WEBHOOK_QUEUE = 'notification_webhook';
	const NOTIFICATION_PHONE_QUEUE = 'notification_phone';
	const API_CALLS_QUEUE = 'api_calls';
	const UPDATE_VAULTS_QUEUE = 'update_vaults';
	const LISTENER_QUEUE = 'listeners';
	const DEFAULT = 'default';
	const QUEUES_ALL = [
		self::DEFAULT,
		self::NOTIFICATION_TELEGRAM_QUEUE,
		self::NOTIFICATION_EMAIL_QUEUE,
		self::NOTIFICATION_WEBHOOK_QUEUE,
		self::NOTIFICATION_PHONE_QUEUE,
		self::API_CALLS_QUEUE,
		self::UPDATE_VAULTS_QUEUE,
		self::LISTENER_QUEUE,
	];
}
