<?php

namespace App\Enum;

class NotificationGatewayType
{
	const TELEGRAM = 'telegram';
	const MAIL = 'mail';
	const WEBHOOK = 'webhook';
	const PHONE = 'phone';
	const WEBPUSH = 'webpush';
	const ACTIVE_NOTIFICATIONS = [
		self::TELEGRAM,
		self::MAIL,
		self::PHONE,
		//		self::WEBPUSH,
		self::WEBHOOK,
	];
}
