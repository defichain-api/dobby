<?php

namespace App\Providers;

use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
	protected function authorization(): void
	{
		Horizon::auth(function () {
			return true;
		});
	}
}
