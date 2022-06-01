<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\BroadcastMessage */
class BroadcastMessageCollection extends ResourceCollection
{
	public $resource = BroadcastMessageResource::class;
	public static $wrap = null;

	public function toArray($request): array
	{
		return [
			'messages' => $this->collection,
		];
	}
}
