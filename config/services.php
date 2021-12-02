<?php

return [
	'mailgun'          => [
		'domain'   => env('MAILGUN_DOMAIN'),
		'secret'   => env('MAILGUN_SECRET'),
		'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
	],
	'telegram-bot-api' => [
		'token' => env('TELEGRAM_TOKEN'),
	],
];
