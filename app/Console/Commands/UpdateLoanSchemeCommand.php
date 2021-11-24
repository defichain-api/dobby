<?php

namespace App\Console\Commands;

use App\Api\Exceptions\DefichainApiException;
use App\ApiClient\DefiChainApiClient;
use App\Models\Service\LoanSchemeService;
use Illuminate\Console\Command;

class UpdateLoanSchemeCommand extends Command
{
	protected $signature = 'update:loan_schemes';
	protected $description = 'Update all available loan schemes';

	public function handle(DefiChainApiClient $apiClient, LoanSchemeService $loanSchemeService)
	{
		try {
			$rawLoanSchemes = $apiClient->getLoanSchemes();
		} catch (DefichainApiException) {
			return;
		}

		$loanSchemeService->update($rawLoanSchemes);
	}
}
