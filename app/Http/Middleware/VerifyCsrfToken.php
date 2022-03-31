<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
	protected $except = [
		'telegram-bot',
		'telegram-bot/*',
		'twilio/webhook',
	];
}
