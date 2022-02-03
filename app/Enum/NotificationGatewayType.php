<?php

namespace App\Enum;

class NotificationGatewayType
{
	const TELEGRAM = 'telegram';
	const MAIL = 'email';
	const WEBHOOK = 'webhook';
	const WEBPUSH = 'webpush';
	const ACTIVE_NOTIFICATIONS = [
		self::TELEGRAM,
		self::MAIL,
//		self::WEBPUSH,
		self::WEBHOOK,
	];
}
