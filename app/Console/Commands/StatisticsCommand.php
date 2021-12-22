<?php

namespace App\Console\Commands;

use App\Models\Service\StatisticService;
use Illuminate\Console\Command;

class StatisticsCommand extends Command
{
	protected $signature = 'statistic:create';
	protected $description = 'Create the statistics';

	public function handle(StatisticService $statisticService): void
	{
		$statisticService->updateUserCount()
			->updateVaultCount()
			->updateCollateralSum()
			->updateLoanSum()
			->updateAvgRatio()
			->updateMedianRatio();
	}
}
