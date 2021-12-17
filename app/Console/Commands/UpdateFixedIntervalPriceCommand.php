<?php

namespace App\Console\Commands;

use App\Api\Exceptions\OceanApiException;
use App\ApiClient\OceanApiClient;
use App\Models\Service\FixedIntervalPriceService;
use Illuminate\Console\Command;

class UpdateFixedIntervalPriceCommand extends Command
{
	protected $signature = 'update:fixed_interval_price';
	protected $description = 'Update fixed interval prices';

	public function handle(OceanApiClient $apiClient, FixedIntervalPriceService $fixedIntervalPriceService)
	{
		try {
			$rawPrices = $apiClient->getFixedIntervalPrices();
		} catch (OceanApiException) {
			return;
		}

		$fixedIntervalPriceService->update($rawPrices);
	}
}
