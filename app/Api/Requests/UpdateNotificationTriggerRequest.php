<?php

namespace App\Api\Requests;

use App\Enum\NotificationTriggerType;
use App\Rules\UserGatewayRule;
use App\Rules\UserTriggerRule;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class UpdateNotificationTriggerRequest extends ApiRequest
{
	#[ArrayShape(['triggerId' => "string[]", 'ratio' => "string[]", 'type' => "string[]", 'gateways.*' => "string[]"])]
	public function rules(): array
	{
		/** @var \App\Models\User $requestingUser */
		$requestingUser = $this->get('user');

		return [
			'triggerId'  => [
				'required',
				'int',
				'exists:notification_triggers,id',
				new UserTriggerRule($requestingUser, $this->triggerId()),
			],
			'ratio'      => ['required', 'int'],
			'type'       => ['required', Rule::in(NotificationTriggerType::ALL)],
			'gateways.*' => ['required', 'min:1', new UserGatewayRule($requestingUser)],
		];
	}

	#[ArrayShape(['triggerId.exists' => "string", 'type.in' => "string"])]
	public function messages(): array
	{
		return [
			'triggerId.exists' => 'Setup a trigger first.',
			'type.in'          => sprintf('possible values are: %s', implode(', ', NotificationTriggerType::ALL)),
		];
	}

	public function triggerId(): int
	{
		return $this->input('triggerId');
	}

	public function ratio(): int
	{
		return $this->input('ratio');
	}

	public function type(): string
	{
		return $this->input('type');
	}

	public function gateways(): array
	{
		return $this->input('gateways');
	}
}
