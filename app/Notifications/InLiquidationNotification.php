<?php

namespace App\Notifications;

use App\Models\NotificationTrigger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramMessage;

class InLiquidationNotification extends BaseNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(NotificationTrigger $notificationTrigger): TelegramMessage
	{
		return TelegramMessage::create()
			->content(
				__('notifications/telegram/in_liquidation.message', [
					'vault_id'     => str_truncate_middle($this->vault->vaultId, 15, '...'),
					'block_height' => $this->vault->liquidationHeight,
				])
			)
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}
}