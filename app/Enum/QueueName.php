<?php

namespace App\Enum;

class QueueName
{
	const NOTIFICATION_TELEGRAM_QUEUE = 'notification_telegram';
	const NOTIFICATION_EMAIL_QUEUE = 'notification_email';
	const NOTIFICATION_WEBHOOK_QUEUE = 'notification_webhook';
	const API_CALLS_QUEUE = 'api_calls';
	const UPDATE_VAULTS_QUEUE = 'update_vaults';
}
