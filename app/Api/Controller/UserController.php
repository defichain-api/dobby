<?php

namespace App\Api\Controller;

use App\Api\Requests\UpdateUserRequest;
use App\Api\Service\UserService;
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

	public function updateUserSetting(UpdateUserRequest $request, UserService $service): JsonResponse
	{
		$service->update($request);

		return response()->json([
			'state'   => 'ok',
			'message' => 'user settings updated',
		], Response::HTTP_OK);
	}

	public function deleteUser(Request $request, UserService $service): JsonResponse
	{
		/** @var \App\Models\User $user */
		$user = $request->get('user');
		if (!$service->delete($user)) {
			return response()->json([
				'state'   => 'error',
				'message' => 'could not delete user',
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		return response()->json([
			'state'   => 'ok',
			'message' => 'deleted user',
		], Response::HTTP_OK);
	}
}
