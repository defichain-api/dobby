<?php

namespace App\Notifications;

use App\Api\Service\CollateralRatioRepository;
use App\Api\Service\VaultRepository;
use App\Enum\NotificationGatewayType;
use App\Enum\NotificationTriggerType;
use App\Models\NotificationTrigger;
use App\Models\Vault;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;
use Spatie\WebhookServer\WebhookCall;

class VaultNextRatioNotification extends BaseTriggerNotification
{
	protected CollateralRatioRepository $ratioRepository;

	public function __construct(Vault $vault, ?string $vaultName = null)
	{
		parent::__construct($vault, $vaultName);
		$this->ratioRepository = app(CollateralRatioRepository::class);
	}

	/**
	 * @throws \App\Api\Exceptions\OceanApiException
	 */
	public function toTelegram(NotificationTrigger $notificationTrigger): TelegramMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::TRIGGER_NOTIFICATION)
			->messageTriggerUsed(NotificationTriggerType::NEXT_RATIO);

		return TelegramMessage::create()
			->content(
				__('notifications/telegram/next_ratio.message', [
					'vault_id'       => str_truncate_middle($this->vault->vaultId, 15, '...'),
					'vault_name'     => $this->vaultName ?? '',
					'vault_deeplink' => sprintf(config('links.vault_info_deeplink'), $this->vault->vaultId),
					'next_ratio'     => $this->vault->nextCollateralRatio,
					'block_diff'     => $this->ratioRepository->diffToNextTick(),
					'diff_min'       => $this->ratioRepository->minutesToNextTick(),
					'trigger_ratio'  => $notificationTrigger->ratio,
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
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}

	public function toMail(NotificationTrigger $notificationTrigger): MailMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::MAIL)
			->messageTriggerUsed(NotificationTriggerType::TRIGGER_NOTIFICATION)
			->messageTriggerUsed(NotificationTriggerType::NEXT_RATIO);

		return (new MailMessage)
			->subject(sprintf('%s - %s', __('notifications/mail/next_ratio.subject'), config('app.name')))
			->markdown('mail.notification.next_ratio', [
				'notificationTrigger' => $notificationTrigger,
				'vault'               => $this->vault,
				'vaultName'           => $this->vaultName,
				'ratioRepository'     => $this->ratioRepository,
			]);
	}

	public function toPhone(NotificationTrigger $notificationTrigger): array
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::PHONE)
			->messageTriggerUsed($notificationTrigger->type == NotificationTriggerType::INFO ?
				NotificationTriggerType::INFO : NotificationTriggerType::WARNING)
			->messageTriggerUsed(NotificationTriggerType::NEXT_RATIO);

		return [
			'user'       => $notificationTrigger->user(),
			'vault'      => $notificationTrigger->vault,
			'retryCount' => 0,
		];
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 * @throws \App\Api\Exceptions\OceanApiException
	 */
	public function toWebhook(NotificationTrigger $notificationTrigger): WebhookCall
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::WEBHOOK)
			->messageTriggerUsed(NotificationTriggerType::TRIGGER_NOTIFICATION)
			->messageTriggerUsed(NotificationTriggerType::NEXT_RATIO);

		return WebhookCall::create()
			->url($notificationTrigger->routeNotificationForWebhook())
			->payload([
				'type'    => NotificationTriggerType::NEXT_RATIO,
				'message' => 'vaults next ratio triggered this info notification',
				'data'    => [
					'vaultId'                         => $this->vault->vaultId,
					'vaultName'                       => $this->vaultName,
					'vaultDeeplink'                   => sprintf(config('links.vault_info_deeplink'),
						$this->vault->vaultId),
					'ratioTriggered'                  => $notificationTrigger->ratio,
					'currentRatio'                    => $this->vault->collateralRatio,
					'nextRatio'                       => $this->vault->nextCollateralRatio,
					'ratioActiveInMin'                => $this->ratioRepository->minutesToNextTick(),
					'ratioActiveInBlocks'             => $this->ratioRepository->diffToNextTick(),
					'loanSchemeMinCollaterationRatio' => $this->vault->loanScheme->minCollaterationRatio,
					'collateralAmount'                => $this->formatNumberForTrigger($notificationTrigger,
						$this->vault->collateralValue, 2),
					'loanValue'                       => $this->formatNumberForTrigger($notificationTrigger,
						$this->vault->loanValue,
						2),
					'difference'                      => app(VaultRepository::class)->calculateCollateralDifference($this->vault,
						$notificationTrigger->ratio),
				],
			])->useSecret($notificationTrigger->vaultId);
	}
}
