<?php

namespace App\Channel;

use Illuminate\Notifications\Notification;

class PhoneChannel extends Notification
{
	public function send($notifiable, Notification $notification)
	{
		ray('phone channel');
		/** @var \App\Jobs\PhoneCallJob $phoneCallJob */
		$phoneCallJob = $notification->toPhone($notifiable);
		if (is_null($phoneCallJob)) {
			return;
		}
		$phoneCallJob->dispatchSync();
	}
}
