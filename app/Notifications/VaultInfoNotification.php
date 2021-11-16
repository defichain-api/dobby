<?php

namespace App\Notifications;

use App\Models\NotificationTrigger;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramFile;

class VaultInfoNotification extends BaseNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(NotificationTrigger $notificationTrigger): TelegramFile
	{
		$vault = $notificationTrigger->vault;

		return TelegramFile::create()
			->content(
				__('notifications/telegram/info.message', [
					'ratio'             => $notificationTrigger->ratio,
					'current_ratio'     => round($vault->collateralRatio, 2),
					'collateral_amount' => round($vault->collateralValue, 2),
					'loan_value'        => $vault->loanValue,
					'difference'        => round(abs($vault->collateralValue - $vault->loanValue), 2),
				])
			)
			->file(storage_path('app/notification_images/telegram_info.png'), 'photo')
			->buttonWithCallback(__('bot/snooze.cooldown_times.30'), sprintf('snooze_%s_30', $notificationTrigger->id))
			->buttonWithCallback(__('bot/snooze.cooldown_times.60'), sprintf('snooze_%s_60', $notificationTrigger->id))
			->buttonWithCallback(__('bot/snooze.cooldown_times.180'),
				sprintf('snooze_%s_180', $notificationTrigger->id))
			->buttonWithCallback(__('bot/snooze.cooldown_times.360'),
				sprintf('snooze_%s_360', $notificationTrigger->id))
			->button(__('notifications/telegram/info.button'), config('app.url'));
	}

	public function toMail(NotificationTrigger $notifiable): MailMessage
	{
		return (new MailMessage)
			->subject(__('notifications/mail/info.subject'))
			->line('The introduction to the notification.')
			->action('Notification Action', url('/'))
			->line('Thank you for using our application!');
	}

	public function toWebhook(NotificationTrigger $notifiable): void
	{

	}
}
