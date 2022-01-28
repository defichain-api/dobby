<?php

namespace App\Api\Requests;

use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SetupRequest extends ApiRequest
{
	#[ArrayShape(['language' => "array", 'uiTheme' => "array", 'ownerAddresses' => "string[]"])]
	public function rules(): array
	{
		return [
			'language'       => ['required', 'string', Rule::in(config('app.available_locales'))],
			'uiTheme'        => ['required', 'string', Rule::in(config('app.available_themes'))],
			'ownerAddresses' => ['sometimes', 'array'],
		];
	}

	#[ArrayShape(['language.in' => "string", 'uiTheme.in' => "string"])]
	public function messages(): array
	{
		return [
			'language.in' => sprintf('possible values are: %s', implode(', ', config('app.available_locales'))),
			'uiTheme.in'  => sprintf('possible values are: %s', implode(', ', config('app.available_themes'))),
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
