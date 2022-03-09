<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class NotificationGatewayException extends Exception
{
	#[Pure]
	public static function message(string $type, string $message): self
	{
		return new self(sprintf('gateway type %s: %s', $type, $message));
	}
}
