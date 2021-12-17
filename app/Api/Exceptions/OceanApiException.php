<?php

namespace App\Api\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class OceanApiException extends \Exception
{
	#[Pure]
	public static function message(string $method, Exception $previous): self
	{
		return new self(sprintf('method %s failed with message %s', $method, $previous->getMessage()), 0, $previous);
	}
}
