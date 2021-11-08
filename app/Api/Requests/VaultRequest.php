<?php

namespace App\Api\Requests;

use JetBrains\PhpStorm\ArrayShape;

class VaultRequest extends ApiRequest
{
	#[ArrayShape(['vaultId' => "string[]"])]
	public function rules(): array
	{
		return [
			'vaultId' => ['required', 'string'],
		];
	}

	public function getVaultId(): string
	{
		return $this->input('vaultId');
	}
}
