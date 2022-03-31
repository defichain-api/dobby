<?php

namespace App\Enum;

class TwilioWebhookState
{
	const SUCCESS = 'success';
	const FAILED = 'failed';
	const NO_ANSWER = 'no_answer';
	const RETRY = 'retry'; // used for busy or not answering
}
