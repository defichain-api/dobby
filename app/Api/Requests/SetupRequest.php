<?php

namespace App\Api\Requests;

use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SetupRequest extends ApiRequest
{
	#[ArrayShape(['ownerAddresses' => "string[]"])]
	public function rules(): array
	{
		return [
			'ownerAddresses' => ['sometimes', 'array'],
		];
	}

	public function hasOwnerAddresses(): bool
	{
		return count($this->ownerAddresses()) > 0;
	}

	public function ownerAddresses(): array
	{
		return $this->input('ownerAddresses') ?? [];
	}
}
