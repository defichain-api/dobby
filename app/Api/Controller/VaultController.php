<?php

namespace App\Api\Controller;

use App\Api\Exceptions\DefichainApiException;
use App\Api\Requests\CreateVaultRequest;
use App\Api\Requests\DeleteVaultRequest;
use App\Api\Service\VaultService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VaultController
{
	public function createUserVault(CreateVaultRequest $request, VaultService $vaultService): JsonResponse
	{
		try {
			if (!$vaultService->setVaultForUser($request->get('user'), $request->vaultId())) {
				return response()->json([
					'state'   => 'error',
					'message' => 'vault id not valid',
				], Response::HTTP_BAD_REQUEST);
			}
		} catch (DefichainApiException $e) {
			return response()->json([
				'state'   => 'error',
				'message' => sprintf('could not lookup vault id from API with error message: %s', $e->getMessage()),
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		return response()->json([
			'state'   => 'ok',
			'message' => 'vault added to users repository',
		], Response::HTTP_OK);
	}

	public function deleteUserVault(DeleteVaultRequest $request, VaultService $vaultService): JsonResponse
	{
		$vaultService->detachVaultFromUser($request->get('user'), $request->vaultId());

		return response()->json([
			'state'   => 'ok',
			'message' => 'removed vault from users repository',
		], Response::HTTP_OK);
	}
}
