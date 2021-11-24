<?php

namespace App\Api\Controller;

use App\Api\Requests\CreateNotificationGatewayRequest;
use App\Api\Requests\DeleteNotificationGatewayRequest;
use App\Api\Service\NotificationGatewayService;
use App\Http\Resources\NotificationGatewayCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationGatewayController
{
	public function getGateways(Request $request): NotificationGatewayCollection
	{
		/** @var \App\Models\User $user */
		$user = $request->get('user');

		return new NotificationGatewayCollection(
			$user->gateways
		);
	}

	public function createGateway(
		CreateNotificationGatewayRequest $request,
		NotificationGatewayService       $service
	): JsonResponse {
		$success = $service->create($request);
		if (!$success) {
			return response()->json([
				'state'   => 'error',
				'message' => 'failed to create the notification gateway',
			], Response::HTTP_BAD_REQUEST);
		}

		return response()->json([
			'state'   => 'ok',
			'message' => 'notification gateway is created',
		], Response::HTTP_OK);
	}

	public function deleteGateway(
		DeleteNotificationGatewayRequest $request,
		NotificationGatewayService       $service
	): JsonResponse {
		$success = $service->delete($request);

		if (!$success) {
			return response()->json([
				'state'   => 'error',
				'message' => 'user can\'t delete this gateway',
			], Response::HTTP_BAD_REQUEST);
		}

		return response()->json([
			'state'   => 'ok',
			'message' => 'notification gateway deleted',
		], Response::HTTP_OK);
	}
}
