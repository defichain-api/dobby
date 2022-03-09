<?php

namespace App\Exceptions;

use App\Models\User;
use Exception;
use JetBrains\PhpStorm\Pure;

class PaymentException extends Exception
{
	#[Pure]
	public static function message(float $amount, User $user): self
	{
		return new self(sprintf('user %s can not pay amount %f DFI, balance too low', $user->id, $amount), 0);
	}
}
