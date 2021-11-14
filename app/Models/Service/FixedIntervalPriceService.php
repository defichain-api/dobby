<?php

namespace App\Models\Service;

use App\Models\FixedIntervalPrice;
use Carbon\Carbon;

class FixedIntervalPriceService
{
	public function update(array $rawPrices): void
	{
		foreach ($rawPrices as $rawPrice) {
			FixedIntervalPrice::updateOrCreate([
				'priceFeedId' => $rawPrice['priceFeedId'],
			], [
				'activePrice' => $rawPrice['activePrice'],
				'nextPrice'   => $rawPrice['nextPrice'],
				'timestamp'   => Carbon::parse($rawPrice['timestamp']),
				'isLive'      => $rawPrice['isLive'],
			]);
		}
	}
}
