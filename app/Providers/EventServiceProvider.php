<?php

namespace App\Providers;

use App\Events\VaultUpdatingNextRatioEvent;
use App\Events\VaultUpdatingStateEvent;
use App\Listeners\VaultUpdatingNextRatioListener;
use App\Listeners\VaultUpdatingStateListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	protected $listen = [
		VaultUpdatingNextRatioEvent::class => [
			VaultUpdatingNextRatioListener::class,
		],
		VaultUpdatingStateEvent::class     => [
			VaultUpdatingStateListener::class,
		],
	];

	public function boot(): void
	{
	}
}
