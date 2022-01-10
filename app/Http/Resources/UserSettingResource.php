<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/** @mixin \App\Models\UserSetting */
class UserSettingResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'language'        => $this->language,
			'theme'           => $this->theme,
			'summaryInterval' => $this->summary_interval,
			'user'            => new UserResource($this->whenLoaded('user')),
		];
	}
}
