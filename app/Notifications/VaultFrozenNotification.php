<?php

namespace App\Notifications;

use App\Enum\NotificationGatewayType;
use App\Enum\NotificationTriggerType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;
use Spatie\WebhookServer\WebhookCall;

class VaultFrozenNotification extends BaseUserNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(User $notificationTrigger): TelegramMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::FROZEN);

		return TelegramMessage::create()
			->content(
				__('notifications/telegram/frozen.message', [
					'vault_id'       => str_truncate_middle($this->vault->vaultId, 15, '...'),
					'vault_name'     => $this->vaultName ?? '',
					'vault_deeplink' => $this->vault->deeplink(),
					'channel_url'    => config('links.defichain_announcement_channel'),
				])
			)
			->button(__('notifications/telegram/buttons.visit_website'), config('app.frontend_url'));
	}

	public function toMail(User $user): MailMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::MAIL)
			->messageTriggerUsed(NotificationTriggerType::FROZEN);

		return (new MailMessage)
			->subject(sprintf('%s - %s', __('notifications/mail/frozen.subject'), config('app.name')))
			->markdown('mail.notification.frozen', [
				'vault'     => $this->vault,
				'vaultName' => $this->vaultName,
			]);
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toWebhook(User $user): WebhookCall
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::WEBHOOK)
			->messageTriggerUsed(NotificationTriggerType::FROZEN);

		return WebhookCall::create()
			->url($user->routeNotificationForWebhook())
			->payload([
				'type'    => NotificationTriggerType::FROZEN,
				'message' => 'vault switched to state ' . NotificationTriggerType::FROZEN,
				'data'    => [
					'vaultId'       => $this->vault->vaultId,
					'vaultName'     => $this->vaultName,
					'vaultDeeplink' => $this->vault->deeplink(),
				],
			])->useSecret($user->id);
	}
}
