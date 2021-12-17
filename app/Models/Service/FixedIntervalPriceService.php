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
				'priceFeedId' => $rawPrice['id'],
			], [
				'priceBase'   => $rawPrice['price']['token'],
				'activePrice' => (float) $rawPrice['price']['aggregated']['amount'],
				'nextPrice'   => -1, //tbd
				'blockHeight' => $rawPrice['price']['block']['height'],
				'timestamp'   => Carbon::parse($rawPrice['price']['block']['time']),
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

		return round($amount * $fixedPrice->activePrice, 2);
	}
}
