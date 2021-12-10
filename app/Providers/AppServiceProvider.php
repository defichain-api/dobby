<?php

namespace App\Providers;

use App\Channel\WebhookChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
		// webhook notification channel definition
	    Notification::extend('webhook', function ($app) {
			return new WebhookChannel();
	    });
    }
}
