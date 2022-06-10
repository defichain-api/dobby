<?php

namespace App\Rules;

use App\ApiClient\PhoneCallService;
use Illuminate\Contracts\Validation\Rule;

class PhoneNumberRule implements Rule
{
	public function __construct(protected PhoneCallService $phoneCallService)
	{
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function passes($attribute, $value): bool
	{
		return ($this->phoneCallService->validatePhoneNumber($value))['isValid'];
	}

	public function message(): string
	{
		return 'Phone number validation failed';
	}
}
