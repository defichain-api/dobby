<?php

namespace App\Notifications;

use App\Api\Service\VaultRepository;
use App\Enum\NotificationGatewayType;
use App\Enum\NotificationTriggerType;
use App\Http\BotmanConversation\TelegramMessageService;
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

	/**
	 * @throws \BotMan\BotMan\Exceptions\Base\BotManException
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toTelegram(User $user): TelegramMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::SUMMARY);
		$routeNotificationForTelegram = $user->routeNotificationForTelegram();
		$telegramMessageService = app(TelegramMessageService::class);

		$summary = '';
		$message = __('notifications/telegram/current_summary.intro');
		foreach ($this->vaultsData($user) as $vault) {
			/** @var Vault $vault */
			ray($vault);
			$summary .= sprintf("%s: %s %% | ",
				$vault['vault_name'] ?? str_truncate_middle($vault['vault_id'], 8), $vault['next_ratio']
			);
			$message .= __('notifications/telegram/current_summary.vault_details', [
					'vault_id'          => $vault['vault_id'],
					'vault_deeplink'    => $vault['vault_deeplink'],
					'min_col_ratio'     => $vault['min_col_ratio'],
					'current_ratio'     => $vault['current_ratio'],
					'collateral_amount' => $vault['collateral_amount'],
					'loan_value'        => $vault['loan_value'],
				]) . "\r\n\r\n###############################\r\n\r\n";
		}

		$telegramMessageService->send(
			substr_replace($summary, "", -3),
			$routeNotificationForTelegram
		);

		if (strlen($message) > 4000) {
			$messageSplitted = str_split($message, 4000);
			for ($i = 0; $i < count($messageSplitted) - 1; $i++) {
				$telegramMessageService->send(
					$messageSplitted[$i],
					$routeNotificationForTelegram
				);
			}
			ray($messageSplitted);

			return TelegramMessage::create()
				->content($messageSplitted[count($messageSplitted) - 1])
				->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
		}

		return TelegramMessage::create()
			->content($message)
			->button(__('notifications/telegram/buttons.visit_website'), config('app.url'));
	}

	public function toMail(User $user): MailMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::MAIL)
			->messageTriggerUsed(NotificationTriggerType::SUMMARY);

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
			->messageTriggerUsed(NotificationTriggerType::SUMMARY);

		return WebhookCall::create()
			->url($user->routeNotificationForWebhook())
			->payload([
				'type'    => NotificationTriggerType::SUMMARY,
				'message' => 'vault/s summary',
				'data'    => [
					'vaults' => $this->vaultsData($user),
				],
			])->useSecret($user->id);
	}

	protected function vaultsData(User $user): array
	{
		return app(VaultRepository::class)->vaultsDataForUser($user);
	}

	protected function cooldownIdentifier(string $type): string
	{
		return sprintf('%s_%s', get_class($this), $type);
	}
}
