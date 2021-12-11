<?php

namespace App\Api\Controller;

use App\Api\Requests\CreateNotificationGatewayRequest;
use App\Api\Requests\DeleteNotificationGatewayRequest;
use App\Api\Requests\TestNotificationGatewayRequest;
use App\Api\Service\NotificationGatewayService;
use App\Http\Resources\NotificationGatewayCollection;
use App\Http\Resources\NotificationGatewayResource;
use App\Notifications\DemoNotification;
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
		try {
			$gateway = $service->create($request);

			return response()->json([
				'state'   => 'ok',
				'message' => 'notification gateway is created',
				'data'    => new NotificationGatewayResource($gateway),
			], Response::HTTP_OK);
		} catch (\Exception) {
			return response()->json([
				'state'   => 'error',
				'message' => 'failed to create the notification gateway',
			], Response::HTTP_BAD_REQUEST);
		}
	}

	public function testGateway(
		TestNotificationGatewayRequest $request,
	): JsonResponse {
		/** @var \App\Models\User $user */
		$user = $request->get('user');
		$user->notify(new DemoNotification($request->type()));

		return response()->json([
			'state'   => 'ok',
			'message' => sprintf('test notification to %s gateway sent', $request->type()),
		], Response::HTTP_OK);
	}

	public function deleteGateway(
		DeleteNotificationGatewayRequest $request,
		NotificationGatewayService       $service
	): JsonResponse {
		$success = $service->deleteWithRequest($request);

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
