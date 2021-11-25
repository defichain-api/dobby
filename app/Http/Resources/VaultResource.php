<?php

namespace App\Http\Resources;

use App\Models\Service\FixedIntervalPriceService;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

/** @mixin \App\Models\Vault */
class VaultResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'vaultId'            => $this->vaultId,
			'ownerAddress'       => $this->ownerAddress,
			'loanScheme'         => [
				'id'            => $this->loanScheme->name,
				'minCollateral' => $this->loanScheme->minCollaterationRatio,
			],
			'state'              => $this->state,
			'collateralAmounts'  => $this->amountsToArray($this->collateralAmounts),
			'loanAmounts'        => $this->amountsToArray($this->loanAmounts),
			'interestAmounts'    => $this->amountsToArray($this->interestAmounts),
			'collateralValue'    => $this->collateralValue,
			'loanValue'          => $this->loanValue,
			'interestValue'      => $this->interestValue,
			'informativeRatio'   => $this->informativeRatio,
			'collateralRatio'    => $this->collateralRatio,
			'liquidationHeight'  => $this->liquidationHeight,
			'batchCount'         => $this->batchCount,
			'liquidationPenalty' => $this->liquidationPenalty,
			'batches'            => $this->batches,
		];
	}

	public function amountsToArray(array $rawData): array
	{
		$data = [];
		foreach ($rawData as $item) {
			$explodedItem = Str::of($item)->explode('@');
			$amount = (float) $explodedItem->first();
			$token = $explodedItem->last();
			$valueUsd = rescue(fn() => app(FixedIntervalPriceService::class)->calculateValueForToken($token, $amount)
				, null, false);
			$data[] = [
				'raw'      => $item,
				'amount'   => $amount,
				'token'    => $token,
				$this->mergeWhen(isset($valueUsd), ['valueUsd' => $valueUsd]),
			];
		}

		return $data;
	}
}
