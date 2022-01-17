<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class WebAppController extends Controller
{
	public function index():JsonResponse
	{
		return response()->json([
			'message' => 'frontend is not available',
		]);
	}
}
