<?php

namespace App\Api\Service;

use App\ApiClient\DefiChainApiClient;
use App\Models\LoanScheme;
use App\Models\User;
use App\Models\Vault;
use Exception;

class VaultRepository
{
	protected DefiChainApiClient $apiClient;

	public function __construct()
	{
		$this->apiClient = new DefiChainApiClient();
	}

	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
	public function setVaultsForUser(User $user, array $ownerAddresses): bool
	{
		$vaults = $this->apiClient->getMultipleVaults($ownerAddresses);
		if (count($vaults) === 0) {
			return false;
		}

		foreach ($vaults as $vaultRaw) {
			$vault = $this->createOrUpdate($vaultRaw);
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

	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
	public function updateVaults(array $vaultIds): bool
	{
		$vaults = $this->apiClient->getMultipleVaults($vaultIds);
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

	protected function userHasVaultId(User $user, string $vaultId): bool
	{
		return $user->vaults->contains($vaultId);
	}

	protected function createOrUpdate(array $data): Vault
	{
		$loanSchemes = LoanScheme::all();

		return Vault::updateOrCreate([
			'vaultId' => (string) $data['vaultId'],
		], [
			'loanSchemeId'       => $loanSchemes->where('name', '=', $data['loanSchemeId'])->first()->id,
			'ownerAddress'       => $data['ownerAddress'],
			'state'              => $data['state'],
			'collateralAmounts'  => $data['collateralAmounts'],
			'loanAmounts'        => $data['loanAmounts'],
			'interestAmounts'    => $data['interestAmounts'],
			'collateralValue'    => $data['collateralValue'],
			'loanValue'          => $data['loanValue'],
			'interestValue'      => $data['interestValue'],
			'informativeRatio'   => $data['informativeRatio'],
			'collateralRatio'    => $data['collateralRatio'],
			'liquidationHeight'  => $data['liquidationHeight'],
			'batchCount'         => $data['batchCount'],
			'liquidationPenalty' => $data['liquidationPenalty'],
			'batches'            => $data['batches'],
		]);
	}
}
