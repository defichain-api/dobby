<?php

namespace App\Api\Requests;

use App\Rules\UserGatewayRule;
use JetBrains\PhpStorm\ArrayShape;

class CreateNotificationTriggerRequest extends ApiRequest
{
	#[ArrayShape(['vaultId' => "string[]", 'ratio' => "string[]", 'gateways.*' => "string[]"])]
	public function rules(): array
	{
		/** @var \App\Models\User $requestingUser */
		$requestingUser = $this->get('user');

		return [
			'vaultId'    => ['required', 'exists:vaults,vaultId'],
			'ratio'      => ['required', 'int'],
			'gateways.*' => ['required', 'min:1', new UserGatewayRule($requestingUser)],
		];
	}

	#[ArrayShape(['vaultId.exists' => "string"])]
	public function messages(): array
	{
		return [
			'vaultId.exists' => 'The vault has to be setup first',
		];
	}

	public function vaultId(): string
	{
		return $this->input('vaultId');
	}

	public function ratio(): int
	{
		return $this->input('ratio');
	}

	public function gateways(): array
	{
		return $this->input('gateways');
	}
}
