<?php

namespace App\Models\Concerns;

use App\Enum\NotificationGatewayType;
use App\Models\Repository\NotificationGatewayRepository;

trait UseNotificationConfig
{
	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function routeNotificationForTelegram(): int
	{
		return app(NotificationGatewayRepository::class)->telegram($this)->value;
	}

	public function telegramGateway()
	{
		return $this->gateways()->where('type', NotificationGatewayType::TELEGRAM)->first();
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function routeNotificationForMail(): string
	{
		return app(NotificationGatewayRepository::class)->mail($this)->value;
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function routeNotificationForWebhook(): string
	{
		return app(NotificationGatewayRepository::class)->webhook($this)->value;
	}

	public function hasGateway(string $type): bool
	{
		return $this->gateways()->where('type', $type)->count() > 0;
	}
}
