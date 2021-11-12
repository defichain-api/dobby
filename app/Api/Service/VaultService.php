<?php

namespace App\Api\Service;

use App\ApiClient\DefiChainApiClient;
use App\Models\LoanScheme;
use App\Models\User;
use App\Models\Vault;

class VaultService
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
		if (count($vaults['data']) === 0) {
			return false;
		}

		foreach ($vaults['data'] as $vaultRaw) {
			$vault = $this->create($vaultRaw);
			$this->attachVaultToUser($vault, $user);
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
		$user->vaults()->detach([$vaultId]);
	}

	protected function attachVaultToUser(Vault $vault, User $user): void
	{
		$user->vaults()->attach([$vault->vaultId]);
	}

	protected function create(array $data): Vault
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
