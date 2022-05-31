<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\BroadcastMessage */
class BroadcastMessageResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'headline'   => $this->headline,
			'message'    => $this->message,
			'type'       => $this->type,
			'startTime'  => $this->startTime,
			'endTime'    => $this->endTime,
		];
	}
}
