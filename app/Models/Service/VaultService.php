<?php

namespace App\Models\Service;

use App\Models\Vault;

class VaultService
{
	/**
	 * calculates, how much collateralization to add to reach the target ratio
	 */
	public function calculateCollateralDifference(Vault $vault, int $targetRatio): float
	{
		return round(($vault->loanValue * ($targetRatio / 100)) - $vault->collateralValue, 2);
	}
}
