<?php

namespace App\Api\Requests;

use App\Enum\NotificationGatewayType;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class DeleteNotificationGatewayRequest extends ApiRequest
{
	public function rules(): array
	{
		return [
			'gateway_id'  => ['required', 'integer', 'exists:notification_gateways,id'],
		];
	}

	public function gatewayId(): int
	{
		return $this->input('gateway_id');
	}
}
