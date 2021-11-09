<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
	public function boot(): void
	{
		parent::boot();
		Horizon::night();
	}

	protected function gate(): void
	{
		Horizon::auth(function ($request) {
			return auth()->check();
		});
	}
}
