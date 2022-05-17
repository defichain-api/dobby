<?php

namespace App\Http\Requests;

use App\Api\Requests\ApiRequest;
use App\Models\User;
use JetBrains\PhpStorm\ArrayShape;

class TwilioTestcallWebhookRequest extends ApiRequest
{
	#[ArrayShape([
		'status'    => "string[]",
		'dobby_key' => "string[]",
	])] public function rules(): array
	{
		return [
			'status'    => ['required', 'string'],
			'dobby_key' => ['required', 'string', 'exists:users,id'],
		];
	}

	public function authorize(): bool
	{
		// allow always as the verification is done in the TwilioWebhookRequestValidatorMiddleware
		return true;
	}

	public function status(): string
	{
		return $this->get('status');
	}

	public function dobbyUser(): User
	{
		return User::where('id', $this->dobbyKey())->first();
	}

	public function dobbyKey(): string
	{
		return $this->get('dobby_key');
	}
}
