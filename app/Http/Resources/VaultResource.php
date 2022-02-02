<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Vault */
class VaultResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'vaultId'             => $this->vaultId,
			'name'                => $this->pivot->name ?? '',
			'ownerAddress'        => $this->ownerAddress,
			'loanScheme'          => [
				'id'            => $this->loanScheme->name,
				'minCollateral' => $this->loanScheme->minCollaterationRatio,
			],
			'state'               => $this->state,
			'collateralAmounts'   => $this->amountsToArray($this->collateralAmounts),
			'loanAmounts'         => $this->amountsToArray($this->loanAmounts),
			'interestAmounts'     => $this->amountsToArray($this->interestAmounts),
			'collateralValue'     => $this->collateralValue,
			'loanValue'           => $this->loanValue,
			'interestValue'       => $this->interestValue,
			'informativeRatio'    => $this->informativeRatio,
			'collateralRatio'     => $this->collateralRatio,
			'nextCollateralRatio' => $this->nextCollateralRatio,
			'liquidationHeight'   => $this->liquidationHeight,
			'batchCount'          => $this->batchCount,
			'liquidationPenalty'  => $this->liquidationPenalty,
			'batches'             => $this->batches,
		];
	}

	public function amountsToArray(array $rawData): array
	{
		if (count($rawData) === 0) {
			return [];
		}
		$data = [];
		foreach ($rawData as $item) {
			if (is_string($item)) {
				break;
			}
			$amount = (float) $item['amount'];
			$priceUsd = isset($item['activePrice']['active']['amount']) ? (float) $item['activePrice']['active']['amount'] : 1;
			$token = $item['symbol'];
			$name = $item['name'];
			$valueUsd = rescue(fn() => round($amount * $priceUsd, 2), null, false);
			$data[] = [
				'amount' => $amount,
				'token'  => $token,
				'name'   => $name,
				$this->mergeWhen(isset($valueUsd), ['valueUsd' => $valueUsd]),
			];
		}

		return $data;
	}
}
