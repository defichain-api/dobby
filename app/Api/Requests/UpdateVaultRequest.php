<?php

namespace App\Api\Requests;

use JetBrains\PhpStorm\ArrayShape;

class UpdateVaultRequest extends ApiRequest
{
	#[ArrayShape(['name' => "string[]"])]
	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
		];
	}

	public function name(): string
	{
		return $this->input('name');
	}
}
