<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Payment */
class PaymentResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'reason'    => $this->reason,
			'amountDfi' => $this->amountDfi,
			'booked_at' => $this->created_at,
		];
	}
}
