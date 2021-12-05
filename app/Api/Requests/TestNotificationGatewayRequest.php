<?php

namespace App\Api\Requests;

use App\Enum\NotificationGatewayType;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class TestNotificationGatewayRequest extends ApiRequest
{
	#[ArrayShape(['type' => "array"])]
	public function rules(): array
	{
		return [
			'type'  => ['required', Rule::in(NotificationGatewayType::ACTIVE_NOTIFICATIONS)],
		];
	}

	#[ArrayShape(['type.in' => "string"])]
	public function messages(): array
	{
		return [
			'type.in' => sprintf('possible values are: %s',
				implode(', ', NotificationGatewayType::ACTIVE_NOTIFICATIONS)),
		];
	}

	public function type(): string
	{
		return $this->input('type');
	}
}
