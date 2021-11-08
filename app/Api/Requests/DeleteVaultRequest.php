<?php

namespace App\Api\Requests;

use JetBrains\PhpStorm\ArrayShape;
use Validator;

class DeleteVaultRequest extends ApiRequest
{
	#[ArrayShape(['vaultId' => "string[]"])]
	public function rules(): array
	{
		return [
			'vaultId' => ['required', 'string', 'exists:user_vault,vaultId'],
		];
	}

	public function getVaultId(): string
	{
		return $this->input('vaultId');
	}
}
