<?php

namespace App\Api\Controller;

use App\Models\Vault;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HealthController
{
	public function systemHealth(): JsonResponse
	{
//		$checkRedis =
		return response()->json([], Response::HTTP_OK);
	}

	public function vaultHealth(Vault $vault, int $ratio): JsonResponse
	{
		$checkOk = $vault->nextCollateralRatio > $ratio;

		return response()->json([
			'checkOk' => $checkOk,
		], $checkOk ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY);
	}
}
