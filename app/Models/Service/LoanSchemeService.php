<?php

namespace App\Models\Service;

use App\Models\LoanScheme;

class LoanSchemeService
{
	public function update(array $rawLoanSchemes): void
	{
		foreach ($rawLoanSchemes as $rawLoanScheme) {
			LoanScheme::updateOrCreate([
				'name' => $rawLoanScheme['id'],
			], [
				'minCollaterationRatio' => (int) $rawLoanScheme['minColRatio'],
				'interestRate'          => $rawLoanScheme['interestRate'],
			]);
		}
	}
}
