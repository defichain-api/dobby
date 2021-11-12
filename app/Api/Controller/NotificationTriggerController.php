<?php

namespace App\Api\Controller;

use App\Api\Requests\CreateNotificationTriggerRequest;
use App\Api\Requests\UpdateNotificationTriggerRequest;
use App\Api\Service\NotificationTriggerService;
use App\Http\Resources\NotificationTriggerCollection;
use App\Http\Resources\NotificationTriggerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationTriggerController
{
	public function getTrigger(Request $request): NotificationTriggerCollection
	{
		/** @var \App\Models\User $user */
		$user = $request->get('user');

		return new NotificationTriggerCollection(
			$user->notificationTrigger()
		);
	}

	public function createTrigger(
		CreateNotificationTriggerRequest $request,
		NotificationTriggerService       $service
	): JsonResponse {
		$notificationTrigger = $service->create($request);

		return response()->json([
			'state'   => 'ok',
			'message' => 'notification trigger created',
			'trigger' => new NotificationTriggerResource($notificationTrigger),
		], Response::HTTP_OK);
	}

	public function updateTrigger(
		UpdateNotificationTriggerRequest $request,
		NotificationTriggerService       $service
	): JsonResponse {
		$notificationTrigger = $service->update($request);

		return response()->json([
			'state'   => 'ok',
			'message' => 'notification trigger updated',
			'trigger' => new NotificationTriggerResource($notificationTrigger),
		], Response::HTTP_OK);
	}

	public function deleteTrigger()
	{

	}
}
