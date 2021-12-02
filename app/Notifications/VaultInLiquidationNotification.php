<?php

namespace App\Notifications;

use App\Enum\NotificationTriggerType;
use App\Models\NotificationTrigger;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;
use Spatie\WebhookServer\WebhookCall;

class VaultInLiquidationNotification extends BaseUserNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(User $notificationTrigger): TelegramMessage
	{
		return TelegramMessage::create()
			->content(
				__('notifications/telegram/in_liquidation.message', [
					'vault_id'       => str_truncate_middle($this->vault->vaultId, 15, '...'),
					'vault_deeplink' => sprintf(config('links.vault_info_deeplink'), $this->vault->vaultId),
					'block_height'   => $this->vault->liquidationHeight,
				])
			)
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}

	public function toMail(User $user): MailMessage
	{
		return (new MailMessage)
			->subject(sprintf('%s - %s', __('notifications/mail/in_liquidation.subject'), config('app.name')))
			->markdown('mail.notification.in_liquidation', [
				'vault' => $this->vault,
			]);
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toWebhook(User $user): WebhookCall
	{
		return WebhookCall::create()
			->url($user->routeNotificationForWebhook())
			->payload([
				'type' => NotificationTriggerType::IN_LIQUIDATION,
				'data' => [
					'vaultId'       => $this->vault->vaultId,
					'vaultDeeplink' => sprintf(config('links.vault_info_deeplink'), $this->vault->vaultId),
					'blockHeight'   => $this->vault->liquidationHeight,
				],
			])->useSecret($user->id);
	}
}
