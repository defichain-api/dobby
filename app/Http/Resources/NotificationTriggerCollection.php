<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\NotificationTrigger */
class NotificationTriggerCollection extends ResourceCollection
{
	public $resource = NotificationTriggerResource::class;

	public function toArray($request): array
	{
		return [
			'data' => $this->collection,
		];
	}
}
