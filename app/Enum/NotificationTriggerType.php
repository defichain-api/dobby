<?php

namespace App\Enum;

class NotificationTriggerType
{
	const INFO = 'info';
	const WARNING = 'warning';
	const DAILY = 'daily';
	const IN_LIQUIDATION = 'inLiquidation';
	const MAY_LIQUIDATION = 'mayLiquidate';
	const LIQUIDATED = 'liquidated';
	const ALL = [
		self::INFO,
		self::WARNING,
		self::DAILY,
		self::IN_LIQUIDATION,
		self::MAY_LIQUIDATION,
		self::LIQUIDATED,
	];
}
