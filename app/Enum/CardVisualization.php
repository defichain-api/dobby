<?php

namespace App\Enum;

class CardVisualization
{
	const AUTO = 'auto';
	const CARD = 'card';
	const CAROUSEL = 'carousel';
	const ALL = [
		self::AUTO,
		self::CARD,
		self::CAROUSEL,
	];
}
