<?php

namespace App\Enum;

class NotificationGatewayType
{
	const TELEGRAM = 'telegram';
	const MAIL = 'mail';
	const WEBHOOK = 'webhook';
	const PHONE = 'phone';
	const ACTIVE_NOTIFICATIONS = [
		self::TELEGRAM,
		self::MAIL,
		self::PHONE,
		self::WEBHOOK,
	];
}
