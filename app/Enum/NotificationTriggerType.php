<?php

namespace App\Enum;

class NotificationTriggerType
{
	const INFO = 'info';
	const WARNING = 'warning';
	const ALL = [
		self::INFO,
		self::WARNING,
	];
}
