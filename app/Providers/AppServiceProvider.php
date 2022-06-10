<?php

namespace App\Providers;

use App\Channel\PhoneChannel;
use App\Channel\WebhookChannel;
use App\Enum\NotificationGatewayType;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		// webhook notification channel definition
		Notification::extend(NotificationGatewayType::WEBHOOK, function ($app) {
			return new WebhookChannel();
		});
		// phone notification channel definition
		Notification::extend(NotificationGatewayType::PHONE, function ($app) {
			return new PhoneChannel();
		});
	}
}
