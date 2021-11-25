<?php

namespace App\Models\Service;

use App\Exceptions\FixedIntervalPriceNotAvailableException;
use App\Models\FixedIntervalPrice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

	/**
	 * @throws FixedIntervalPriceNotAvailableException
	 */
	public function calculateValueForToken(string $token, float $amount): float
	{
		try {
			$fixedPrice = FixedIntervalPrice::where('priceBase', $token)->firstOrFail();
		} catch (ModelNotFoundException) {
			throw FixedIntervalPriceNotAvailableException::token($token);
		}

		return $amount * $fixedPrice->activePrice;
	}
}
