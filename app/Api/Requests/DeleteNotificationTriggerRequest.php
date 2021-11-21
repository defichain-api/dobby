<?php

namespace App\Api\Requests;

use JetBrains\PhpStorm\ArrayShape;

class DeleteNotificationTriggerRequest extends ApiRequest
{
	#[ArrayShape(['triggerId' => "string[]"])]
	public function rules(): array
	{
		return [
			'triggerId' => ['required', 'int', 'exists:notification_triggers,id'],
		];
	}

	public function triggerId(): int
	{
		return $this->input('triggerId');
	}
}
