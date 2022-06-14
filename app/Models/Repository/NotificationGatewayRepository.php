<?php

namespace App\Models\Repository;

use App\Enum\NotificationGatewayType;
use App\Exceptions\NotificationGatewayException;
use App\Models\NotificationGateway;
use App\Models\NotificationTrigger;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotificationGatewayRepository
{
	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function telegram(User|NotificationTrigger $model): NotificationGateway
	{
		try {
			return $model->gateways()->where('type', NotificationGatewayType::TELEGRAM)->firstOrFail();
		} catch (ModelNotFoundException) {
			throw NotificationGatewayException::message(NotificationGatewayType::TELEGRAM, 'not available');
		}
	}

	public function removeTelegram(User|NotificationTrigger $model): bool
	{
		try {
			$notificationGateway = $this->telegram($model);
			\Log::info('deleting blocked telegram gateway', [
				'message'     => 'user blocked telegram bot',
				'user'        => $notificationGateway->user->id,
				'telegram_id' => $notificationGateway->value,
			]);
			return $notificationGateway->delete();
		} catch (NotificationGatewayException) {
			return false;
		}
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function mail(User|NotificationTrigger $model): NotificationGateway
	{
		try {
			return $model->gateways()->where('type', NotificationGatewayType::MAIL)->firstOrFail();
		} catch (ModelNotFoundException) {
			throw NotificationGatewayException::message(NotificationGatewayType::MAIL, 'not available');
		}
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function webhook(User|NotificationTrigger $model): NotificationGateway
	{
		try {
			return $model->gateways()->where('type', NotificationGatewayType::WEBHOOK)->firstOrFail();
		} catch (ModelNotFoundException) {
			throw NotificationGatewayException::message(NotificationGatewayType::WEBHOOK, 'not available');
		}
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function phone(User|NotificationTrigger $model): NotificationGateway
	{
		try {
			return $model->gateways()->where('type', NotificationGatewayType::PHONE)->firstOrFail();
		} catch (ModelNotFoundException) {
			throw NotificationGatewayException::message(NotificationGatewayType::PHONE, 'not available');
		}
	}
}
