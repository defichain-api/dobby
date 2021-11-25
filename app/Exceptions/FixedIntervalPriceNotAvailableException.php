<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class FixedIntervalPriceNotAvailableException extends Exception
{
	#[Pure]
	public static function token(string $token): self
	{
		return new self(sprintf('token %s not available', $token));
	}
}
