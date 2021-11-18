<?php

namespace App\Enum;

class NotificationTriggerType
{
	const INFO = 'info';
	const WARNING = 'warning';
	const DAILY = 'daily';
	const IN_LIQUIDATION = 'in_liquidation';
	const LIQUIDATED = 'liquidated';
	const ALL = [
		self::INFO,
		self::WARNING,
		self::DAILY,
		self::IN_LIQUIDATION,
		self::LIQUIDATED,
	];
}
