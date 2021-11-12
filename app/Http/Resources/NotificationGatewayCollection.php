<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\NotificationGateway */
class NotificationGatewayCollection extends ResourceCollection
{
	public $resource = NotificationGatewayResource::class;

	public function toArray($request): array
	{
		return [
			'data' => $this->collection,
		];
	}
}
