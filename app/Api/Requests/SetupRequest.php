<?php

namespace App\Api\Requests;

use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SetupRequest extends ApiRequest
{
	#[ArrayShape(['language' => "array", 'theme' => "array", 'ownerAddresses' => "string[]"])]
	public function rules(): array
	{
		return [
			'language'       => ['sometimes', 'string', Rule::in(config('app.available_locales'))],
			'theme'          => ['sometimes', 'string', Rule::in(config('app.available_themes'))],
			'ownerAddresses' => ['sometimes', 'array'],
		];
	}

	#[ArrayShape(['language.in' => "string", 'theme.in' => "string"])]
	public function messages(): array
	{
		return [
			'language.in' => sprintf('possible values are: %s', implode(', ', config('app.available_locales'))),
			'theme.in'    => sprintf('possible values are: %s', implode(', ', config('app.available_themes'))),
		];
	}

	public function language(): string
	{
		return $this->input('language');
	}

	public function theme(): string
	{
		return $this->input('theme');
	}

	public function hasOwnerAddresses(): bool
	{
		return count($this->ownerAddresses()) > 0;
	}

	public function ownerAddresses(): array
	{
		return $this->input('ownerAddresses') ?? [];
	}
}
