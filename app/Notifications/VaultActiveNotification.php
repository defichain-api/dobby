<?php

namespace App\Notifications;

use App\Enum\NotificationGatewayType;
use App\Enum\NotificationTriggerType;
use App\Models\NotificationTrigger;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;
use Spatie\WebhookServer\WebhookCall;

class VaultActiveNotification extends BaseUserNotification implements ShouldQueue
{
	use Queueable;

	public function __construct(Vault $vault, protected string $vaultOriginalState, ?string $vaultName = null)
	{
		parent::__construct($vault, $vaultName);
	}

	public function toTelegram(User $notificationTrigger): TelegramMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::ACTIVE);

		return TelegramMessage::create()
			->content(
				__('notifications/telegram/active.message', [
					'vault_id'       => str_truncate_middle($this->vault->vaultId, 15, '...'),
					'vault_name'     => $this->vaultName ?? '',
					'vault_deeplink' => sprintf(config('links.vault_info_deeplink'), $this->vault->vaultId),
					'original_state' => __(sprintf('vault/states.%s', $this->vaultOriginalState)),
				])
			)
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}

	public function toMail(User $user): MailMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::MAIL)
			->messageTriggerUsed(NotificationTriggerType::ACTIVE);

		return (new MailMessage)
			->subject(sprintf('%s - %s', __('notifications/mail/active.subject'), config('app.name')))
			->markdown('mail.notification.active', [
				'vault'       => $this->vault,
				'vaultName'   => $this->vaultName,
				'stateBefore' => __(sprintf('vault/states.%s', $this->vaultOriginalState)),
			]);
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toWebhook(User $user): WebhookCall
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::WEBHOOK)
			->messageTriggerUsed(NotificationTriggerType::ACTIVE);

		return WebhookCall::create()
			->url($user->routeNotificationForWebhook())
			->payload([
				'type' => NotificationTriggerType::ACTIVE,
				'data' => [
					'vaultId'       => $this->vault->vaultId,
					'vaultName'     => $this->vaultName,
					'stateBefore'   => $this->vaultOriginalState,
					'vaultDeeplink' => sprintf(config('links.vault_info_deeplink'), $this->vault->vaultId),
				],
			])->useSecret($user->id);
	}
}
