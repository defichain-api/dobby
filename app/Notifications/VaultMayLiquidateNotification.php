<?php

namespace App\Notifications;

use App\Api\Service\VaultRepository;
use App\Enum\NotificationGatewayType;
use App\Enum\NotificationTriggerType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;
use Spatie\WebhookServer\WebhookCall;

class VaultMayLiquidateNotification extends BaseUserNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(User $user): TelegramMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::MAY_LIQUIDATION);

		return TelegramMessage::create()
			->content(
				__('notifications/telegram/may_liquidation.message', [
					'vault_id'       => str_truncate_middle($this->vault->vaultId, 15, '...'),
					'vault_name'     => $this->vaultName ?? '',
					'vault_deeplink' => $this->vault->deeplink(),
					'difference'     => app(VaultRepository::class)->calculateCollateralDifference($this->vault, 300),
				])
			)
			->button(__('notifications/telegram/buttons.visit_website'), config('app.frontend_url'));
	}

	public function toMail(User $user): MailMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::MAIL)
			->messageTriggerUsed(NotificationTriggerType::MAY_LIQUIDATION);

		return (new MailMessage)
			->subject(sprintf('%s - %s', __('notifications/mail/may_liquidate.subject'), config('app.name')))
			->markdown('mail.notification.may_liquidate', [
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
			->messageTriggerUsed(NotificationTriggerType::MAY_LIQUIDATION);

		return WebhookCall::create()
			->url($user->routeNotificationForWebhook())
			->payload([
				'type'    => NotificationTriggerType::MAY_LIQUIDATION,
				'message' => 'vault switched to state ' . NotificationTriggerType::MAY_LIQUIDATION,
				'data'    => [
					'vaultId'       => $this->vault->vaultId,
					'vaultName'     => $this->vaultName,
					'vaultDeeplink' => $this->vault->deeplink(),
					'difference'    => app(VaultRepository::class)->calculateCollateralDifference($this->vault, 300),
				],
			])->useSecret($user->id);
	}
}
