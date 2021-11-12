<?php

namespace App\Api\Controller;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LanguageController
{
	public function languageList(): JsonResponse
	{
		return response()->json([
			'languages' => config('app.available_locales'),
		], Response::HTTP_OK);
	}

	public function languageIso(string $iso): JsonResponse
	{
		if (!in_array($iso, config('app.available_locales'))) {
			return response()->json([
				'state'   => 'error',
				'message' => 'this locale is not available yet',
			], Response::HTTP_BAD_REQUEST);
		}

		return response()->json([
			'lang_code' => $iso,
			'data'      => __('spa'),
		], Response::HTTP_OK);
	}
}
