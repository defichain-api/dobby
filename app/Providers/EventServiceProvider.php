<?php

namespace App\Providers;

use App\Events\VaultUpdatingRatioEvent;
use App\Events\VaultUpdatingStateEvent;
use App\Listeners\VaultUpdatingRatioListener;
use App\Listeners\VaultUpdatingStateListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	protected $listen = [
		VaultUpdatingRatioEvent::class => [
			VaultUpdatingRatioListener::class,
		],
		VaultUpdatingStateEvent::class => [
			VaultUpdatingStateListener::class,
		],
	];

	public function boot(): void
	{
	}
}
