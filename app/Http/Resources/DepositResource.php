<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Deposit */
class DepositResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'txid'          => $this->txid,
			'senderAddress' => $this->senderAddress,
			'block'         => $this->block,
			'amountDfi'     => $this->amountDfi,
			'booked_at'     => $this->created_at,
		];
	}
}
