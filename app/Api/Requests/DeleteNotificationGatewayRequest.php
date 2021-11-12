<?php

namespace App\Api\Requests;

use App\Enum\NotificationGatewayType;
use App\Rules\UserGatewayRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class DeleteNotificationGatewayRequest extends ApiRequest
{
	public function rules(): array
	{
		/** @var \App\Models\User $requestingUser */
		$requestingUser = $this->get('user');

		return [
			'gateway_id'  => ['required', 'integer', new UserGatewayRule($requestingUser)],
		];
	}

	public function gatewayId(): int
	{
		return $this->input('gateway_id');
	}
}
