<?php

namespace App\Enum;

class NotificationGatewayType
{
	const TELEGRAM = 'telegram';
	const EMAIL = 'email';
	const WEBHOOK = 'webhook';
	const WEBPUSH = 'webpush';
	const ACTIVE_NOTIFICATIONS = [
		self::TELEGRAM,
		self::WEBHOOK,
	];
}
