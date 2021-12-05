<?php

namespace App\Api\Controller;

use App\Http\Resources\StatisticCollection;
use App\Models\Statistic;

class StatisticController
{
	public function getStatistics(): StatisticCollection
	{
		return new StatisticCollection(
			Statistic::whereDate('date', '<', today())
				->orderByDesc('date')
				->paginate(21)
		);
	}
}
