<?php

namespace App\Enum;

class NotificationTriggerType
{
	const INFO = 'info';
	const WARNING = 'warning';
	const DAILY = 'daily';
	const IN_LIQUIDATION = 'inLiquidation';
	const MAY_LIQUIDATION = 'mayLiquidate';
	const FROZEN = 'frozen';
	const ACTIVE = 'active';
	const LIQUIDATED = 'liquidated';
	const DEMO = 'demo';
	const ALL = [
		self::INFO,
		self::WARNING,
		self::DAILY,
		self::IN_LIQUIDATION,
		self::MAY_LIQUIDATION,
		self::FROZEN,
		self::ACTIVE,
		self::LIQUIDATED,
		self::DEMO,
	];
}
