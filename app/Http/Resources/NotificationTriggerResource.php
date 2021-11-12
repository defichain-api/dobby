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
			'gateways'  => NotificationGatewayResource::collection($this->gateways),
			'vault'     => new VaultResource($this->whenLoaded('vault')),
		];
	}
}
