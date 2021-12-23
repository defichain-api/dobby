<?php

namespace App\Api\Controller;

use App\Api\Service\CollateralRatioRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PriceTickerController
{
	public function __construct(protected CollateralRatioRepository $ratioRepository)
	{
	}

	public function getNextTick(): JsonResponse
	{
		try {
			return response()->json($this->ratioRepository->summaryArray(), Response::HTTP_OK);
		} catch (\Exception) {
			return response()->json([
				'state'   => 'error',
				'message' => 'not able to calculate the next tick at the moment',
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}
