<?php

namespace App\Notifications;

use App\Channel\WebhookChannel;
use App\Enum\CooldownTypes;
use App\Enum\NotificationGatewayType;
use App\Models\NotificationTrigger;
use App\Models\Vault;

class BaseTriggerNotification extends BaseNotification
{
	public function __construct(protected Vault $vault)
	{
		parent::__construct();
	}

	public function via(NotificationTrigger $trigger): array
	{
		$this->notifiable = $trigger;
		$methods = [];
		if ($trigger->hasGateway(NotificationGatewayType::TELEGRAM)
			&& $trigger->cooldown(CooldownTypes::TELEGRAM_NOTIFICATION)->passed()) {
			$methods[] = NotificationGatewayType::TELEGRAM;
		}
		if ($trigger->hasGateway(NotificationGatewayType::WEBHOOK)) {
			$methods[] = NotificationGatewayType::WEBHOOK;
		}
		if ($trigger->hasGateway(NotificationGatewayType::MAIL)
			&& $trigger->cooldown(CooldownTypes::MAIL_NOTIFICATION)->passed()) {
			$methods[] = NotificationGatewayType::MAIL;
		}

		return $methods;
	}

	public function formatNumberForTrigger(NotificationTrigger $trigger, float|int $number, int $decimals = 2): string
	{
		$language = rescue(fn() => $trigger->user()->language, 'en', false);

		return number_format_for_language($number, 2, $language);
	}
}
