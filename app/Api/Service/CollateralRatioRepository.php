<?php

namespace App\Api\Service;

use App\ApiClient\BaseApiClient;
use App\ApiClient\OceanApiClient;
use App\Enum\VaultStates;
use App\Models\Vault;

class CollateralRatioRepository
{
	protected BaseApiClient $apiClient;

	public function __construct()
	{
		$this->apiClient = new OceanApiClient();
	}

	/**
	 * @throws \Exception
	 */
	public function latestPriceTick(): int
	{
		return cache()->remember('latest_price_tick', now()->addSeconds(15), function() {
			$randomVaults = Vault::whereState(VaultStates::ACTIVE)
				->where('collateralValue', '>', 0)
				->where('loanValue', '>', 0)
				->where('collateralRatio', '!=', -1)
				->limit(100)
				->get();

			foreach ($randomVaults as $randomVault) {
				foreach ($randomVault->collateralAmounts as $collateralAmount) {
					if (!isset($collateralAmount['activePrice'])) {
						continue;
					}

					return $collateralAmount['activePrice']['block']['height'];
				}
			}
			throw new \Exception(sprintf('%s: not able to find latest price tick', now()->toDateTimeString()));
		});
	}

	/**
	 * @throws \Exception
	 */
	public function nextPriceTick(): int
	{
		return $this->latestPriceTick() + 120;
	}

	/**
	 * @throws \App\Api\Exceptions\OceanApiException
	 * @throws \Exception
	 */
	public function diffToNextTick(): int
	{
		return $this->nextPriceTick() - $this->apiClient->currentBlockHeight();
	}

	/**
	 * @throws \App\Api\Exceptions\OceanApiException
	 */
	public function minutesToNextTick(): int
	{
		return ceil($this->diffToNextTick() * 0.5);
	}

	/**
	 * @throws \Exception
	 */
	public function summaryArray(): array
	{
		return cache()->remember('next_tick_response', now()->addMinute(), function () {
			return [
				'next_tick'                    => [
					'block_height' => $this->nextPriceTick(),
					'minutes_left' => $this->minutesToNextTick(),
					'time'         => now()->addMinutes($this->minutesToNextTick())->toDateTimeString(),
				],
				'last_price_tick_block_height' => $this->latestPriceTick(),
			];
		});
	}
}
