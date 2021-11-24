<?php

namespace App\Models\Repository;

use App\Enum\NotificationGatewayType;
use App\Exceptions\NotificationGatewayException;
use App\Models\NotificationTrigger;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotificationGatewayRepository
{
	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function telegram(User|NotificationTrigger $model
	): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany|\Illuminate\Database\Eloquent\Relations\HasMany {
		try {
			return $model->gateways()->where('type', NotificationGatewayType::TELEGRAM)->firstOrFail();
		} catch (ModelNotFoundException) {
			throw NotificationGatewayException::message(NotificationGatewayType::TELEGRAM, 'not available');
		}
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function mail(User|NotificationTrigger $model
	): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany|\Illuminate\Database\Eloquent\Relations\HasMany {
		try {
			return $model->gateways()->where('type', NotificationGatewayType::MAIL)->firstOrFail();
		} catch (ModelNotFoundException) {
			throw NotificationGatewayException::message(NotificationGatewayType::MAIL, 'not available');
		}
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function webhook(User|NotificationTrigger $model
	): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany|\Illuminate\Database\Eloquent\Relations\HasMany {
		try {
			return $model->gateways()->where('type', NotificationGatewayType::WEBHOOK)->firstOrFail();
		} catch (ModelNotFoundException) {
			throw NotificationGatewayException::message(NotificationGatewayType::WEBHOOK, 'not available');
		}
	}
}
