<?php

namespace App\Console\Commands;

use App\Api\Exceptions\OceanApiException;
use App\ApiClient\OceanApiClient;
use App\Models\Service\LoanSchemeService;
use Illuminate\Console\Command;

class UpdateLoanSchemeCommand extends Command
{
	protected $signature = 'update:loan_schemes';
	protected $description = 'Update all available loan schemes';

	public function handle(OceanApiClient $apiClient, LoanSchemeService $loanSchemeService)
	{
		try {
			$rawLoanSchemes = $apiClient->getLoanSchemes();
		} catch (OceanApiException) {
			return;
		}

		$loanSchemeService->update($rawLoanSchemes);
	}
}
