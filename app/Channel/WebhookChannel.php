<?php

namespace App\Channel;

use Illuminate\Notifications\Notification;
use Spatie\WebhookServer\WebhookCall;

class WebhookChannel extends Notification
{
	public function send($notifiable, Notification $notification): void
	{
		/** @var WebhookCall $webhook */
		$webhook = $notification->toWebhook($notifiable);
		$webhook->dispatchSync();
	}
}
