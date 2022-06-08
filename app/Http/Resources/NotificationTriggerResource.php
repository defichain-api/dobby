<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\NotificationTrigger */
class NotificationTriggerResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'triggerId' => $this->id,
			'ratio'     => $this->ratio,
			'vaultId'   => $this->vault->vaultId,
			'gateways'  => NotificationGatewayResource::collection($this->gateways),
		];
	}
}
