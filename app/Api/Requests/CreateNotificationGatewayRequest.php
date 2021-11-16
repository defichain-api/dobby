<?php

namespace App\Api\Requests;

use App\Enum\NotificationGatewayType;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class CreateNotificationGatewayRequest extends ApiRequest
{
	#[ArrayShape(['type' => "array", 'value' => "string[]"])]
	public function rules(): array
	{
		$type = $this->input('type');
		$valueRules = '';
		if ($type === NotificationGatewayType::MAIL) {
			$valueRules = 'email:rfc,dns';
		} elseif ($type === NotificationGatewayType::WEBHOOK) {
			$valueRules = 'active_url';
		}

		return [
			'type'  => ['required', Rule::in(NotificationGatewayType::ACTIVE_NOTIFICATIONS)],
			'value' => ['required', 'string', $valueRules],
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

	public function value(): string
	{
		return $this->input('value');
	}
}
