<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Vault;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class TwilioWebhookRequest extends FormRequest
{
	#[ArrayShape([
		'status'      => "string[]",
		'dobby_key'   => "string[]",
		'vault_id'    => "string[]",
		'retry_count' => "string[]",
	])] public function rules(): array
	{
		return [
			'status'      => ['required', 'string'],
			'dobby_key'   => ['required', 'string', 'exists:users,id'],
			'vault_id'    => ['required', 'string', 'exists:vaults,vaultId'],
			'retry_count' => ['required', 'integer', 'min:0'],
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

	public function vault(): Vault
	{
		return Vault::where('vaultId', $this->vaultId())->first();
	}

	public function vaultId(): string
	{
		return $this->get('vault_id');
	}

	public function retryCount(): int
	{
		return $this->get('retry_count');
	}
}
