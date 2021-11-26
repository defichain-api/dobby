<?php

namespace App\Notifications;

use App\Enum\NotificationTriggerType;
use App\Models\NotificationTrigger;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramFile;
use Spatie\WebhookServer\WebhookCall;

class DailySummaryTriggerNotification extends BaseUserNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(User $user): TelegramFile
	{
		return TelegramFile::create()
			->content('test nachricht')
			->file(storage_path('app/img/notification/telegram_daily.png'), 'photo')
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toWebhook(User $user): WebhookCall
	{
		return WebhookCall::create()
			->url($user->routeNotificationForWebhook())
			->payload([
				'type' => NotificationTriggerType::DAILY,
				'data' => [
					'vault_id'          => $this->vault->vaultId,
					'min_col_ratio'     => $this->vault->loanScheme->minCollaterationRatio,
					'current_ratio'     => $this->vault->collateralRatio,
					'collateral_amount' => round($this->vault->collateralValue, 2),
					'loan_value'        => round($this->vault->loanValue, 2),
				],
			])->useSecret($user->id);
	}
}
