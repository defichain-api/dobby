<?php

namespace App\Models\Service;

use App\Models\LoanScheme;

class LoanSchemeService
{
	public function update(array $rawLoanSchemes): void
	{
		foreach ($rawLoanSchemes as $rawLoanScheme) {
			LoanScheme::updateOrCreate([
				'name' => $rawLoanScheme['name'],
			], [
				'minCollaterationRatio' => $rawLoanScheme['minCollaterationRatio'],
				'interestRate'          => $rawLoanScheme['interestRate'],
				'isDefault'             => $rawLoanScheme['isDefault'],
			]);
		}
	}
}
