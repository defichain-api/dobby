<?php

namespace App\Api\Controller;

use App\Enum\SummaryInterval;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListController
{
	public function timezones(): JsonResponse
	{
		return response()->json([
			'timezones' => __('timezones'),
		], Response::HTTP_OK);
	}

	public function summaryInterval(): JsonResponse
	{
		return response()->json([
			'summaryInterval' => SummaryInterval::ALL,
		], Response::HTTP_OK);
	}
}
