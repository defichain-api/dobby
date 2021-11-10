<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\NotificationGateway */
class NotificationGatewayResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'id'       => $this->id,
			'type'     => $this->type,
			'value'    => $this->value,
		];
	}
}
