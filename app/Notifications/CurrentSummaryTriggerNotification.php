<?php

namespace App\Notifications;

use App\Enum\NotificationGatewayType;
use App\Enum\NotificationTriggerType;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;
use Spatie\WebhookServer\WebhookCall;

class CurrentSummaryTriggerNotification extends BaseUserNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(User $user): TelegramMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::DAILY);

		$message = __('notifications/telegram/current_summary.intro') . "\r\n\r\n###############################\r\n\r\n";
		foreach ($this->vaultsData($user) as $vault) {
			$message .= __('notifications/telegram/current_summary.vault_details',
					$vault) . "\r\n\r\n###############################\r\n\r\n";
		}

		return TelegramMessage::create()
			->content($message)
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}

	public function toMail(User $user): MailMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::MAIL)
			->messageTriggerUsed(NotificationTriggerType::DAILY);

		return (new MailMessage)
			->subject(sprintf('%s - %s', __('notifications/mail/current_summary.subject'), config('app.name')))
			->markdown('mail.notification.current_summary', [
				'vaults' => $this->vaultsData($user),
			]);
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toWebhook(User $user): WebhookCall
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::WEBHOOK)
			->messageTriggerUsed(NotificationTriggerType::DAILY);

		return WebhookCall::create()
			->url($user->routeNotificationForWebhook())
			->payload([
				'type' => NotificationTriggerType::DAILY,
				'data' => [
					'vaults' => $this->vaultsData($user),
				],
			])->useSecret($user->id);
	}

	protected function vaultsData(User $user): array
	{
		$data = rescue(fn() => cache()->remember(
			sprintf('user_%s_vaults', $user->id),
			now()->addMinutes(2),
			function () use ($user) {
				$vaults = $user->vaults;
				$vaultData = [];
				$vaults->each(function (Vault $vault) use (&$vaultData, $user) {
					$vaultData[] = [
						'vault_id'          => $vault->vaultId,
						'vault_deeplink'    => sprintf(config('links.vault_info_deeplink'), $vault->vaultId),
						'min_col_ratio'     => $vault->loanScheme->minCollaterationRatio,
						'current_ratio'     => $vault->collateralRatio,
						'collateral_amount' => number_format_for_language($vault->collateralValue, 2, $user->language),
						'loan_value'        => number_format_for_language($vault->loanValue, 2, $user->language),
					];
				});

				return $vaultData;
			}), [], false);

		return $data;
	}

	protected function cooldownIdentifier(string $type): string
	{
		return sprintf('%s_%s', get_class($this), $type);
	}
}
