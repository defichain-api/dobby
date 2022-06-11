<?php

namespace App\HealthChecks;

use Illuminate\Support\Facades\Redis;
use UKFast\HealthCheck\HealthCheck;

class RedisHealthCheck extends HealthCheck
{
	public function name(): string
	{
		return 'redis';
	}

	public function status()
	{
		try {
			Redis::ping();
		} catch (\Exception $e) {
			return $this->problem('Failed to connect to redis', [
				'exception' => $this->exceptionContext($e),
			]);
		}

		return $this->okay();
	}
}
