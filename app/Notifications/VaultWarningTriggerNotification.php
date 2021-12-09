<?php

namespace App\Notifications;

use App\Enum\NotificationGatewayType;
use App\Models\Service\VaultService;
use App\Enum\NotificationTriggerType;
use App\Models\NotificationTrigger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramFile;
use Spatie\WebhookServer\WebhookCall;

class VaultWarningTriggerNotification extends BaseTriggerNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(NotificationTrigger $notificationTrigger): TelegramFile
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::WARNING);

		return TelegramFile::create()
			->content(
				__('notifications/telegram/warning.message', [
					'vault_id'           => str_truncate_middle($this->vault->vaultId, 15, '...'),
					'vault_deeplink'    => sprintf(config('links.vault_info_deeplink'), $this->vault->vaultId),
					'ratio'             => $notificationTrigger->ratio,
					'current_ratio'     => $this->vault->collateralRatio,
					'collateral_amount' => round($this->vault->collateralValue, 2),
					'loan_value'        => round($this->vault->loanValue, 2),
					'difference'        => app(VaultService::class)->calculateCollateralDifference($this->vault,
						$notificationTrigger->ratio),
				])
			)
			->file(storage_path('app/img/notification/telegram_warning.png'), 'photo')
			->buttonWithCallback(__('notifications/telegram/buttons.cooldown_times.30'),
				sprintf('snooze_%s_30', $notificationTrigger->id))
			->buttonWithCallback(__('notifications/telegram/buttons.cooldown_times.60'), sprintf('snooze_%s_60',
				$notificationTrigger->id))
			->buttonWithCallback(__('notifications/telegram/buttons.cooldown_times.180'),
				sprintf('snooze_%s_180', $notificationTrigger->id))
			->buttonWithCallback(__('notifications/telegram/buttons.cooldown_times.360'),
				sprintf('snooze_%s_360', $notificationTrigger->id))
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toWebhook(NotificationTrigger $notificationTrigger): WebhookCall
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::WEBHOOK)
			->messageTriggerUsed(NotificationTriggerType::WARNING);

		return WebhookCall::create()
			->url($notificationTrigger->routeNotificationForWebhook())
			->payload([
				'type' => NotificationTriggerType::WARNING,
				'data' => [
					'vaultId'          => $this->vault->vaultId,
					'ratio'            => $notificationTrigger->ratio,
					'currentRatio'     => $this->vault->collateralRatio,
					'collateralAmount' => $this->formatNumberForTrigger($notificationTrigger, $this->vault->collateralValue),
					'loanValue'        => $this->formatNumberForTrigger($notificationTrigger, $this->vault->loanValue),
					'difference'       => app(VaultService::class)->calculateCollateralDifference($this->vault,
						$notificationTrigger->ratio),
				],
			])->useSecret($notificationTrigger->vaultId);
	}

	public function toMail(NotificationTrigger $notificationTrigger): MailMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::MAIL)
			->messageTriggerUsed(NotificationTriggerType::WARNING);

		return (new MailMessage)
			->subject(sprintf('%s - %s', __('notifications/mail/warning.subject'), config('app.name')))
			->markdown('mail.notification.warning', [
				'notificationTrigger' => $notificationTrigger,
				'vault'               => $this->vault,
			]);
	}
}
