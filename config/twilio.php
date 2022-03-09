<?php

return [
	'account_sid'       => env('TWILIO_ACCOUNT_SID'),
	'auth_token'        => env('TWILIO_AUTH_TOKEN'),
	'phone_number'      => env('TWILIO_PHONE_NUMBER'),
	'main_flow_sid'     => env('TWILIO_MAIN_FLOW_SID'),
	'testcall_flow_sid' => env('TWILIO_TEST_CALL_FLOW_SID'),

	'phone_call_cost' => env('TWILIO_CALL_COSTS', 0.1),
];
