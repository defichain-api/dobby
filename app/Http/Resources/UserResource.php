<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'userId'   => $this->id,
			'settings' => new UserSettingResource($this->setting),
			'vaults'   => VaultResource::collection($this->vaults),
		];
	}
}
