<?php

namespace App\Api\Requests;

use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class UpdateUserRequest extends ApiRequest
{
	#[ArrayShape(['language' => "string[]", 'theme' => "string[]"])]
	public function rules(): array
	{
		return [
			'language' => ['sometimes', 'string', Rule::in(config('app.available_locales'))],
			'theme'    => ['sometimes', 'string', Rule::in(config('app.available_themes'))],
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

	public function hasLanguage(): bool
	{
		return $this->has('language');
	}

	public function hasTheme(): bool
	{
		return $this->has('theme');
	}

	public function language(): string
	{
		return $this->input('language');
	}

	public function theme(): string
	{
		return $this->input('theme');
	}
}
