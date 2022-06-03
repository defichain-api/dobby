<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DefichainAddressLengthRule implements Rule
{
	public function __construct(protected int $a, protected int $b)
	{
	}

	public function passes($attribute, $value): bool
	{
		return strlen($value) == $this->a || strlen($value) == $this->b;
	}

	public function message(): string
	{
		return sprintf('Deposit address (defichain) must have %s or %s chars', $this->a, $this->b);
	}
}
