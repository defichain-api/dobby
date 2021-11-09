<?php

namespace App\Api\Controller;

use App\Api\Requests\SetupRequest;
use App\Api\Service\UserService;
use App\Api\Service\VaultService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SetupController
{
	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
	public function setup(
		SetupRequest $request,
		UserService  $setupService,
		VaultService $userVaultService
	): JsonResponse {
		$user = $setupService->createUser($request);

		if ($request->hasOwnerAddresses()) {
			$userVaultService->setVaultsForUser($user, $request->getOwnerAddresses());
		}

		return response()->json(new UserResource($user), Response::HTTP_OK);
	}
}
