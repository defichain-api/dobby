<?php

namespace App\HealthChecks;

use Artisan;
use Str;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use UKFast\HealthCheck\HealthCheck;

class HorizonRunningCheck extends HealthCheck
{
	public function name(): string
	{
		return 'queue_worker';
	}

	public function status()
	{
		try {
			Artisan::call('horizon:status');
			$output = Artisan::output();
			$horizonRunning = Str::contains($output,'Horizon is running');
		} catch (CommandNotFoundException) {
			return $this->problem('Horizon is not running. Command not found...');
		}

		if (!$horizonRunning) {
			return $this->problem('Horizon is not running');
		}

		return $this->okay();
	}
}
