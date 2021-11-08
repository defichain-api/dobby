<?php

namespace App\Api\Controller;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
	public function getUser(Request $request): JsonResponse
	{
		return response()->json(new UserResource($request->get('user')), Response::HTTP_OK);
	}
}
