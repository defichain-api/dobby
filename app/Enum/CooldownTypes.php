<?php

namespace App\Enum;

class CooldownTypes
{
	const TELEGRAM_NOTIFICATION = 'telegram_notification';
	const MAIL_NOTIFICATION = 'mail_notification';
	const PHONE_NOTIFICATION = 'phone_notification';

	public static function getType(string $notificationType): string
	{
		return match ($notificationType) {
			NotificationGatewayType::PHONE => self::PHONE_NOTIFICATION,
			NotificationGatewayType::MAIL => self::MAIL_NOTIFICATION,
			NotificationGatewayType::TELEGRAM => self::TELEGRAM_NOTIFICATION,
			default => '',
		};
	}
}
