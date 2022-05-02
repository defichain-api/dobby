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

class CurrentSummaryTriggerNotification extends BaseUserNotification implements ShouldQueue
{
	use Queueable;

	public function toTelegram(User $user): TelegramMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::SUMMARY);

		$message = '';
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
