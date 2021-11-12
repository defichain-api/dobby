<?php

namespace App\Api\Service;

use App\Api\Requests\CreateNotificationGatewayRequest;
use App\Api\Requests\DeleteNotificationGatewayRequest;
use App\Enum\NotificationGatewayType;
use App\Models\NotificationGateway;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotificationGatewayService
{
	public function create(CreateNotificationGatewayRequest $request): bool
	{
		try {
			NotificationGateway::create([
				'userId' => $request->get('user')->userId,
				'type'   => $request->type(),
				'value'  => $request->value(),
			]);

			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function createTelegramGateway(string $userId, string $telegramId): NotificationGateway
	{
		return NotificationGateway::create([
			'userId' => $userId,
			'type'   => NotificationGatewayType::TELEGRAM,
			'value'  => $telegramId,
		]);
	}

	public function delete(DeleteNotificationGatewayRequest $request): bool
	{

		try {
			$notificationGateway = NotificationGateway::where('userId', $request->get('user')->userId)
				->where('id', $request->gatewayId())->firstOrFail();
		} catch (ModelNotFoundException $e) {
			return false;
		}

		return $notificationGateway->delete();
	}

	public function hasGatewayForUser(string $userId, string $gatewayType): bool
	{
		try {
			NotificationGateway::where('userId', $userId)
				->where('type', $gatewayType)->firstOrFail();

			return true;
		} catch (ModelNotFoundException $e) {
			return false;
		}
	}
}
