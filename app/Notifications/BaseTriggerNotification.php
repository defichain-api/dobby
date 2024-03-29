<?php

namespace App\Notifications;

use App\Enum\NotificationGatewayType;
use App\Models\NotificationTrigger;
use App\Models\Vault;

class BaseTriggerNotification extends BaseNotification
{
	public function __construct(protected Vault $vault, protected ?string $vaultName = null)
	{
		parent::__construct();
	}

	public function via(NotificationTrigger $trigger): array
	{
		$this->notifiable = $trigger;
		$methods = [];
		if ($trigger->hasGateway(NotificationGatewayType::TELEGRAM)) {
			$methods[] = NotificationGatewayType::TELEGRAM;
		}
		if ($trigger->hasGateway(NotificationGatewayType::WEBHOOK)) {
			$methods[] = NotificationGatewayType::WEBHOOK;
		}
		if ($trigger->hasGateway(NotificationGatewayType::MAIL)) {
			$methods[] = NotificationGatewayType::MAIL;
		}
		if ($trigger->hasGateway(NotificationGatewayType::PHONE)) {
			$methods[] = NotificationGatewayType::PHONE;
		}

		return $methods;
	}

	/**
	 * @return null
	 */
	public function toPhone(NotificationTrigger $notificationTrigger)
	{
		return null;
	}

	public function formatNumberForTrigger(NotificationTrigger $trigger, float|int $number, int $decimals = 2): string
	{
		$language = rescue(fn() => $trigger->user()->setting->language, 'en', false);

		return number_format_for_language($number, 2, $language);
	}
}
