<?php

namespace App\Enum;

class NotificationTriggerType
{
	const TRIGGER_NOTIFICATION = 'info';
	const SUMMARY = 'summary';
	const IN_LIQUIDATION = 'inLiquidation';
	const MAY_LIQUIDATION = 'mayLiquidate';
	const FROZEN = 'frozen';
	const ACTIVE = 'active';
	const LIQUIDATED = 'liquidated';
	const NEXT_RATIO = 'next_ratio';
	const DEMO = 'demo';
	const ALL = [
		self::TRIGGER_NOTIFICATION,
		self::SUMMARY,
		self::IN_LIQUIDATION,
		self::MAY_LIQUIDATION,
		self::FROZEN,
		self::ACTIVE,
		self::LIQUIDATED,
		self::NEXT_RATIO,
		self::DEMO,
	];
}
