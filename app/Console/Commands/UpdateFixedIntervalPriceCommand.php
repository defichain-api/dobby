<?php

namespace App\Console\Commands;

use App\Api\Exceptions\DefichainApiException;
use App\ApiClient\DefiChainApiClient;
use App\Models\Service\FixedIntervalPriceService;
use Illuminate\Console\Command;

class UpdateFixedIntervalPriceCommand extends Command
{
	protected $signature = 'update:fixed_interval_price';
	protected $description = 'Update fixed interval prices';

	public function handle(DefiChainApiClient $apiClient, FixedIntervalPriceService $fixedIntervalPriceService)
	{
		try {
			$rawPrices = $apiClient->getFixedIntervalPrices();
		} catch (DefichainApiException) {
			return;
		}

		$fixedIntervalPriceService->update($rawPrices);
	}
}
