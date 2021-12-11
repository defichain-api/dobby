<?php

namespace App\Api\Service;

use App\Models\User;
use App\Models\Vault;

class VaultRepository
{
	public function vaultsDataForUser(User $user): array
	{
		return rescue(fn() => cache()->remember(
			sprintf('user_%s_vaults', $user->id),
			now()->addMinutes(2),
			function () use ($user) {
				/** @var User $vaults */
				$vaults = $user->vaults;
				$vaultData = [];
				$vaults->each(function (Vault $vault) use (&$vaultData, $user) {
					$vaultData[] = [
						'vault_id'          => $vault->vaultId,
						'vault_name'        => $vault->pivot->name ?? '',
						'vault_deeplink'    => sprintf(config('links.vault_info_deeplink'), $vault->vaultId),
						'min_col_ratio'     => $vault->loanScheme->minCollaterationRatio,
						'current_ratio'     => $vault->collateralRatio === -1 ? 'n/a' : $vault->collateralRatio,
						'collateral_amount' => number_format_for_language($vault->collateralValue, 2, $user->language),
						'loan_value'        => number_format_for_language($vault->loanValue, 2, $user->language),
					];
				});

				return $vaultData;
			}), [], false);
	}

	/**
	 * calculates, how much collateralization to add to reach the target ratio
	 */
	public function calculateCollateralDifference(Vault $vault, int $targetRatio): float
	{
		return round(($vault->loanValue * ($targetRatio / 100)) - $vault->collateralValue, 2);
	}
}
