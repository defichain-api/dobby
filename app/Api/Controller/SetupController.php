<?php

namespace App\Api\Controller;

use App\Api\Requests\SetupRequest;
use App\Api\Service\SetupService;
use App\Api\Service\VaultService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SetupController
{
	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
	public function setup(
		SetupRequest $request,
		SetupService $setupService,
		VaultService $userVaultService
	): JsonResponse {
		$user = $setupService->createUser($request);

		if ($request->hasOwnerAddresses()) {
			$userVaultService->setVaultsForUser($user, $request->getOwnerAddresses());
		}

		return response()->json([
			'user' => $user,
		], Response::HTTP_OK);
	}
}