<?php

namespace App\Api\Service;

use App\ApiClient\OceanApiClient;
use App\Models\LoanScheme;
use App\Models\User;
use App\Models\Vault;
use Exception;
use Str;

class VaultService
{
	public function setVaultsForUser(User $user, array $ownerAddresses): bool
	{
		$vaults = Vault::whereIn('vaultId', $ownerAddresses)
			->orWhereIn('ownerAddress', $ownerAddresses)
			->get();
		if (count($vaults) === 0) {
			return false;
		}

		foreach ($vaults as $vault) {
			try {
				if (!$this->userHasVaultId($user, $vault->vaultId)) {
					$this->attachVaultToUser($vault, $user);
				}
			} catch (Exception) {
				return false;
			}
		}

		return true;
	}

	public function updateVaults(array $vaults): bool
	{
		if (count($vaults) === 0) {
			return false;
		}

		foreach ($vaults as $vaultRaw) {
			$this->createOrUpdate($vaultRaw);
		}

		return true;
	}

	public function setVaultForUser(User $user, string $vaultId): bool
	{
		return $this->setVaultsForUser($user, [$vaultId]);
	}

	public function detachVaultFromUser(User $user, string $vaultId): void
	{
		app(NotificationTriggerService::class)->deleteTriggerForUserVault($user, $vaultId);
		$user->vaults()->detach([$vaultId]);
	}

	protected function attachVaultToUser(Vault $vault, User $user): void
	{
		$user->vaults()->attach([$vault->vaultId]);
	}

	public function setVaultName(User $user, Vault $vault, string $name): bool
	{
		return $user->vaults()->updateExistingPivot($vault->vaultId, ['name' => $name]) > 0;
	}

	protected function createOrUpdate(array $data): void
	{
		// dont update inactive vaults
		if (!isset($data['collateralRatio']) || $data['collateralRatio'] == -1) {
			return;
		}

		$loanSchemes = LoanScheme::all();

		Vault::updateOrCreate([
			'vaultId' => (string) $data['vaultId'],
		], [
			'loanSchemeId'        => $loanSchemes->where('name', '=', $data['loanScheme']['id'])->first()->id,
			'ownerAddress'        => $data['ownerAddress'],
			'state'               => Str::lower($data['state']),
			'collateralAmounts'   => $data['collateralAmounts'] ?? [],
			'loanAmounts'         => $data['loanAmounts'] ?? [],
			'interestAmounts'     => $data['interestAmounts'] ?? [],
			'collateralValue'     => $data['collateralValue'] ?? null,
			'loanValue'           => $data['loanValue'] ?? null,
			'interestValue'       => $data['interestValue'] ?? 0,
			'informativeRatio'    => $data['informativeRatio'] ?? 0,
			'collateralRatio'     => $data['collateralRatio'] ?? null,
			'nextCollateralRatio' => rescue(fn() => $this->calculateNextCollateralRatio(
				$data['collateralAmounts'] ?? [],
				$data['loanAmounts'] ?? []
			), null, false),
			'liquidationHeight'   => $data['liquidationHeight'] ?? null,
			'batchCount'          => $data['batchCount'] ?? 0,
			'liquidationPenalty'  => $data['liquidationPenalty'] ?? 0,
			'batches'             => $data['batches'] ?? [],
		]);
	}

	protected function userHasVaultId(User $user, string $vaultId): bool
	{
		return $user->vaults->contains($vaultId);
	}

	public function calculateNextCollateralRatio(array $colAmounts, array $loanAmounts): float
	{
		if (count($colAmounts) === 0 || count($loanAmounts) === 0) {
			return -1;
		}

		return round($this->nextCollateralValue($colAmounts) / $this->nextLoanValue($loanAmounts) * 100, 2);
	}

	protected function nextCollateralValue(array $colAmounts): float
	{
		$nextAmount = 0.00;
		foreach ($colAmounts as $colAmount) {
			// dUSD has no active price
			if ($colAmount['symbol'] == 'DUSD') {
				$nextPrice = $this->calculateDusdActiveValue();
			} else {
				$nextPrice = $colAmount['activePrice']['next']['amount'] ?? 0;
			}
			$nextAmount += $colAmount['amount'] * $nextPrice;
		}

		return $nextAmount;
	}

	/**
	 * helper function to calculate the next collateral value
	 * starting from block 2877281, the value of dUSD will be reduced by $0.005 every 2880 blocks
	 * to a total of 115,200 blocks (equals ~40 days)
	 */
	protected function calculateDusdActiveValue(): float
	{
		// load ocean api and fetch the current block height
		$blockHeight = app(OceanApiClient::class)->currentBlockHeight();
		if ($blockHeight >= 2877281 + 115200) {
			return 1.00;
		}

		$blocksPassed = $blockHeight - 2877281;
		$ticks = floor($blocksPassed / 2880);

		return 1.2 - ($ticks * 0.005);
	}

	protected function nextLoanValue(array $loanAmounts): float
	{
		$nextLoan = 0.00;
		foreach ($loanAmounts as $loanAmount) {
			if (isset($loanAmount['activePrice'])) {
				$nextLoan += $loanAmount['amount'] * $loanAmount['activePrice']['next']['amount'] ?? 1;
				continue;
			}
			// for dUSD
			$nextLoan += $loanAmount['amount'];
		}

		return $nextLoan;
	}
}
