<?php

namespace App\Api\Service;

use App\ApiClient\DefiChainApiClient;
use App\Models\LoanScheme;
use App\Models\User;
use App\Models\Vault;
use Exception;
use Str;

class VaultService
{
	protected DefiChainApiClient $apiClient;

	public function __construct()
	{
		$this->apiClient = new DefiChainApiClient();
	}

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

	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
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

	protected function createOrUpdate(array $data): Vault
	{
		$loanSchemes = LoanScheme::all();

		return Vault::updateOrCreate([
			'vaultId' => (string) $data['vaultId'],
		], [
			'loanSchemeId'       => $loanSchemes->where('name', '=', $data['loanScheme']['id'])->first()->id,
			'ownerAddress'       => $data['ownerAddress'],
			'state'              => Str::lower($data['state']),
			'collateralAmounts'  => $data['collateralAmounts'] ?? [],
			'loanAmounts'        => $data['loanAmounts'] ?? [],
			'interestAmounts'    => $data['interestAmounts'] ?? [],
			'collateralValue'    => $data['collateralValue'] ?? null,
			'loanValue'          => $data['loanValue'] ?? null,
			'interestValue'      => $data['interestValue'] ?? 0,
			'informativeRatio'   => $data['informativeRatio'] ?? 0,
			'collateralRatio'    => $data['collateralRatio'] ?? null,
			'liquidationHeight'  => $data['liquidationHeight'] ?? null,
			'batchCount'         => $data['batchCount'] ?? 0,
			'liquidationPenalty' => $data['liquidationPenalty'] ?? 0,
			'batches'            => $data['batches'] ?? [],
		]);
	}

	protected function userHasVaultId(User $user, string $vaultId): bool
	{
		return $user->vaults->contains($vaultId);
	}
}
