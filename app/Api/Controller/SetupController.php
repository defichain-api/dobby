<?php

namespace App\Api\Controller;

use App\Api\Requests\SetupRequest;
use App\Api\Service\UserService;
use App\Api\Service\VaultService;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SetupController
{
	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
	public function setup(
		SetupRequest $request,
		VaultService $userVaultService
	): JsonResponse {
		$user = User::create();

		if ($request->hasOwnerAddresses()) {
			$userVaultService->setVaultsForUser($user, $request->ownerAddresses());
		}

		return response()->json(new UserResource($user), Response::HTTP_OK);
	}
}
