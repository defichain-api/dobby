<?php

namespace App\Enum;

enum PhoneCallState: string
{
	// internal states
	case INITIATED = 'initiated';
	// internal & webhook states
	case SUCCESS = 'success';
	case FAILED = 'failed';
	case FAILED_NO_CREDITS = 'failed_no_credits';
	case BUSY = 'busy';
	case NO_ANSWER = 'no_answer';
	case RETRY = 'retry'; // used for busy or not answering
}
