<?php

namespace App\Exceptions;

use Exception;

class NotificationGatewayException extends Exception
{
	public static function message(string $type, string $message): self
	{
		return new self(sprintf('gateway type %s: %s', $type, $message));
	}
}
