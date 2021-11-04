<?php

namespace App\Api\Service;

use App\ApiClient\DefiChainApiClient;
use App\Models\LoanScheme;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Database\QueryException;

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
	public function setVaultsForUser(User $user, array $ownerAddresses)
	{
		$vaults = $this->apiClient->getMultipleVaults($ownerAddresses);

		foreach ($vaults['data'] as $vaultRaw) {
			$vault = $this->createVault($vaultRaw);
			$user->vaults()->attach([$vault->vaultId]);
		}

	}

	protected function createVault(array $data): Vault
	{
		$loanSchemes = LoanScheme::all();
		try {
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
		} catch (QueryException $exception) {
			return Vault::find($data['vaultId']);
		}
	}
}