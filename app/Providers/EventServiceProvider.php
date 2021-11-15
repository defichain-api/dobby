<?php

namespace App\Providers;

use App\Events\VaultUpdatingEvent;
use App\Listeners\VaultUpdatingListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	protected $listen = [
		VaultUpdatingEvent::class => [
			VaultUpdatingListener::class,
		],
	];

	public function boot(): void
	{
	}
}
