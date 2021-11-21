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

	protected function authorization(): void
	{
		Horizon::auth(function () {
			return true;
		});
	}
}
