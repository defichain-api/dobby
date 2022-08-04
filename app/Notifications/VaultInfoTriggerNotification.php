<?php

namespace App\Notifications;

use App\Api\Service\VaultRepository;
use App\Enum\NotificationGatewayType;
use App\Enum\NotificationTriggerType;
use App\Models\NotificationTrigger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;
use Spatie\WebhookServer\WebhookCall;

class VaultInfoTriggerNotification extends BaseTriggerNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(NotificationTrigger $notificationTrigger): TelegramMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::TRIGGER_NOTIFICATION);

		return TelegramMessage::create()
			->content(
				__('notifications/telegram/info.message', [
					'vault_id'          => str_truncate_middle($this->vault->vaultId, 15, '...'),
					'vault_name'        => $this->vaultName ?? '',
					'vault_deeplink'    => sprintf(config('links.vault_info_deeplink'), $this->vault->vaultId),
					'ratio'             => $notificationTrigger->ratio,
					'current_ratio'     => $this->vault->collateralRatio,
					'collateral_amount' => $this->formatNumberForTrigger($notificationTrigger,
						$this->vault->collateralValue, 2),
					'loan_value'        => $this->formatNumberForTrigger($notificationTrigger, $this->vault->loanValue,
						2),
					'difference'        => app(VaultRepository::class)->calculateCollateralDifference($this->vault,
						$notificationTrigger->ratio),
				])
			)
			->buttonWithCallback(__('notifications/telegram/buttons.cooldown_times.30'),
				sprintf('snooze_%s_30', $notificationTrigger->id))
			->buttonWithCallback(__('notifications/telegram/buttons.cooldown_times.60'),
				sprintf('snooze_%s_60', $notificationTrigger->id))
			->buttonWithCallback(__('notifications/telegram/buttons.cooldown_times.180'),
				sprintf('snooze_%s_180', $notificationTrigger->id))
			->buttonWithCallback(__('notifications/telegram/buttons.cooldown_times.360'),
				sprintf('snooze_%s_360', $notificationTrigger->id))
			->buttonWithCallback(__('notifications/telegram/buttons.cooldown_times.720'),
				sprintf('snooze_%s_720', $notificationTrigger->id))
			->button(__('notifications/telegram/buttons.visit_website'), config('app.frontend_url'));
	}

	public function toMail(NotificationTrigger $notificationTrigger): MailMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::MAIL)
			->messageTriggerUsed(NotificationTriggerType::TRIGGER_NOTIFICATION);

		return (new MailMessage)
			->subject(sprintf('%s - %s', __('notifications/mail/warning.subject'), config('app.name')))
			->markdown('mail.notification.info', [
				'notificationTrigger' => $notificationTrigger,
				'vault'               => $this->vault,
				'vaultName'           => $this->vaultName,
			]);
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toWebhook(NotificationTrigger $notificationTrigger): WebhookCall
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::WEBHOOK)
			->messageTriggerUsed(NotificationTriggerType::TRIGGER_NOTIFICATION);

		return WebhookCall::create()
			->url($notificationTrigger->routeNotificationForWebhook())
			->payload([
				'type'    => NotificationTriggerType::TRIGGER_NOTIFICATION,
				'message' => 'vaults ratio triggered this info notification',
				'data'    => [
					'vaultId'          => $this->vault->vaultId,
					'vaultName'        => $this->vaultName,
					'vaultDeeplink'    => sprintf(config('links.vault_info_deeplink'), $this->vault->vaultId),
					'ratioTrigger'     => $notificationTrigger->ratio,
					'currentRatio'     => $this->vault->collateralRatio,
					'collateralAmount' => $this->formatNumberForTrigger($notificationTrigger,
						$this->vault->collateralValue, 2),
					'loanValue'        => $this->formatNumberForTrigger($notificationTrigger, $this->vault->loanValue,
						2),
					'difference'       => app(VaultRepository::class)->calculateCollateralDifference($this->vault,
						$notificationTrigger->ratio),
				],
			])->useSecret($notificationTrigger->vaultId);
	}
}
