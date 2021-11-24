<?php

namespace App\Models\Service;

use App\Models\FixedIntervalPrice;
use Carbon\Carbon;
use Str;

class FixedIntervalPriceService
{
	public function update(array $rawPrices): void
	{
		foreach ($rawPrices as $rawPrice) {
			FixedIntervalPrice::updateOrCreate([
				'priceFeedId' => $rawPrice['priceFeedId'],
			], [
				'priceBase'   => Str::of($rawPrice['priceFeedId'])->explode('/')->first(),
				'activePrice' => $rawPrice['activePrice'],
				'nextPrice'   => $rawPrice['nextPrice'],
				'timestamp'   => Carbon::parse($rawPrice['timestamp']),
				'isLive'      => $rawPrice['isLive'],
			]);
		}
	}
}
