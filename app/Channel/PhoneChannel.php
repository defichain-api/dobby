<?php

namespace App\Channel;

use App\Jobs\PhoneCallJob;
use Illuminate\Notifications\Notification;

class PhoneChannel extends Notification
{
	public function send($notifiable, Notification $notification)
	{
		/** @var \App\Jobs\PhoneCallJob $phoneCallJob */
		$phoneCallJobDetails = $notification->toPhone($notifiable);
		if (is_null($phoneCallJobDetails)) {
			return;
		}
		PhoneCallJob::dispatchSync(
			$phoneCallJobDetails['user'],
			$phoneCallJobDetails['vault'],
			$phoneCallJobDetails['retryCount']
		);
	}
}
