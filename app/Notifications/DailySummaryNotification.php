<?php

namespace App\Notifications;

use App\Enum\NotificationTriggerType;
use App\Models\NotificationTrigger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramFile;
use Spatie\WebhookServer\WebhookCall;

class DailySummaryNotification extends BaseNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(NotificationTrigger $notificationTrigger): TelegramFile
	{
		return TelegramFile::create()
			->content(
				__('notifications/telegram/daily_summary.message', [
					'vault_id'          => str_truncate_middle($this->vault->vaultId, 25, '...'),
					'min_col_ratio'     => $this->vault->loanScheme->minCollaterationRatio,
					'current_ratio'     => $this->vault->collateralRatio,
					'collateral_amount' => round($this->vault->collateralValue, 2),
					'loan_value'        => round($this->vault->loanValue, 2),
				])
			)
			->file(storage_path('app/notification_images/telegram_daily.png'), 'photo')
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toWebhook(NotificationTrigger $notificationTrigger): WebhookCall
	{
		return WebhookCall::create()
			->url($notificationTrigger->webhookGateway()->value)
			->payload([
				'type' => NotificationTriggerType::DAILY,
				'data' => [
					'vault_id'          => $this->vault->vaultId,
					'min_col_ratio'     => $this->vault->loanScheme->minCollaterationRatio,
					'current_ratio'     => $this->vault->collateralRatio,
					'collateral_amount' => round($this->vault->collateralValue, 2),
					'loan_value'        => round($this->vault->loanValue, 2),
				],
			])->useSecret($notificationTrigger->vaultId);
	}
}
