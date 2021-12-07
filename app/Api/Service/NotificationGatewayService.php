<?php

namespace App\Api\Service;

use App\Api\Requests\CreateNotificationGatewayRequest;
use App\Api\Requests\DeleteNotificationGatewayRequest;
use App\Enum\NotificationGatewayType;
use App\Models\NotificationGateway;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotificationGatewayService
{
	public function create(CreateNotificationGatewayRequest $request): NotificationGateway
	{
		return NotificationGateway::create([
			'userId' => $request->get('user')->id,
			'type'   => $request->type(),
			'value'  => $request->value(),
		]);
	}

	public function createOrUpdateTelegramGateway(string $userId, string $telegramId): NotificationGateway
	{
		return NotificationGateway::updateOrCreate([
			'value' => $telegramId,
		], [
			'userId' => $userId,
			'type'   => NotificationGatewayType::TELEGRAM,
		]);
	}

	public function delete(DeleteNotificationGatewayRequest $request): bool
	{

		try {
			$notificationGateway = NotificationGateway::where('userId', $request->get('user')->id)
				->where('id', $request->gatewayId())->firstOrFail();
		} catch (ModelNotFoundException) {
			return false;
		}

		return $notificationGateway->delete();
	}

	public function hasGatewayWithValue(string $senderId, string $gatewayType): bool
	{
		try {
			NotificationGateway::where('value', $senderId)
				->where('type', $gatewayType)->firstOrFail();

			return true;
		} catch (ModelNotFoundException) {
			return false;
		}
	}
}
