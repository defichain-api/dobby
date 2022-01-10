<?php

namespace App\Enum;

class SummaryInterval
{
	const NONE = 'none';
	const DAILY_ONCE = 'daily';
	const DAILY_TWICE = 'daily_2x';
	const DAILY_THRICE = 'daily_3x';
	const HOURLY = 'hourly';
	const ALL = [
		self::NONE,
		self::DAILY_ONCE,
		self::DAILY_TWICE,
		self::DAILY_THRICE,
		self::HOURLY,
	];
}
