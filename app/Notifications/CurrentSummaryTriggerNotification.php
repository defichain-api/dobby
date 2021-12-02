<?php

namespace App\Notifications;

use App\Enum\NotificationTriggerType;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramFile;
use Spatie\WebhookServer\WebhookCall;

class CurrentSummaryTriggerNotification extends BaseUserNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(User $user): TelegramFile
	{
		$message = __('notifications/telegram/current_summary.intro') . "\r\n\r\n###############################\r\n\r\n";
		foreach ($this->vaultsData($user) as $vault) {
			$message .= __('notifications/telegram/current_summary.vault_details',
					$vault) . "\r\n\r\n###############################\r\n\r\n";
		}

		return TelegramFile::create()
			->content($message)
			->file(storage_path('app/img/notification/telegram_daily.png'), 'photo')
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}

	public function toMail(User $user): MailMessage
	{
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
				$vaults->each(function (Vault $vault) use (&$vaultData) {
					$vaultData[] = [
						'vault_id'          => $vault->vaultId,
						'vault_deeplink'    => sprintf(config('links.vault_info_deeplink'), $vault->vaultId),
						'min_col_ratio'     => $vault->loanScheme->minCollaterationRatio,
						'current_ratio'     => $vault->collateralRatio,
						'collateral_amount' => round($vault->collateralValue, 2),
						'loan_value'        => round($vault->loanValue, 2),
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
