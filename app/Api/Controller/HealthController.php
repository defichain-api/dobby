<?php

namespace App\Api\Controller;

use App\Models\Vault;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use UKFast\HealthCheck\Facade\HealthCheck;

class HealthController
{
	public function systemHealth(): JsonResponse
	{
		$env = HealthCheck::passes('env');
		$redis = HealthCheck::passes('redis');
		$database = HealthCheck::passes('database');
		$queueWorker = HealthCheck::passes('queue_worker');

		$checkOk = $env && $redis && $database && $queueWorker;

		return response()->json([
			'envPassed'      => $env,
			'redisPassed'    => $redis,
			'databasePassed' => $database,
			'queueWorker'    => $queueWorker,
		], $checkOk ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	public function vaultHealth(Vault $vault, int $ratio): JsonResponse
	{
		$checkOk = $vault->nextCollateralRatio > $ratio;

		return response()->json([
			'checkOk'   => $checkOk,
			'nextRatio' => $vault->nextCollateralRatio,
		], $checkOk ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY);
	}
}
