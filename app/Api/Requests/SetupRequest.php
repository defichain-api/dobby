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
			'language'       => ['required', 'string', Rule::in(config('app.available_locales'))],
			'theme'          => ['required', 'string', Rule::in(config('app.available_themes'))],
			'ownerAddresses' => ['sometimes', 'array'],
		];
	}

	public function getLanguage(): string
	{
		return $this->input('language');
	}

	public function getTheme(): string
	{
		return $this->input('theme');
	}

	public function hasOwnerAddresses(): bool
	{
		return count($this->input('ownerAddresses') ?? []) > 0;
	}

	public function getOwnerAddresses(): array
	{
		return $this->input('ownerAddresses') ?? [];
	}
}
