<?php

namespace App\Api\Controller;

use App\Channel\PhoneCallChannel;

class PhoneController
{
	public function verify(string $phoneNumber, PhoneCallChannel $phoneCallService)
	{
		return $phoneCallService->validatePhoneNumber($phoneNumber);
	}
}
