<?php

namespace App\Notifications;

use App\Channel\WebhookChannel;
use App\Enum\NotificationGatewayType;
use App\Enum\NotificationTriggerType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;
use Spatie\WebhookServer\WebhookCall;

class DemoNotification extends BaseNotification implements ShouldQueue
{
	use Queueable;

	public function __construct(protected string $channel)
	{
		parent::__construct();
	}

	public function via(User $user): array
	{
		$this->notifiable = $user;

		return match ($this->channel) {
			NotificationGatewayType::TELEGRAM => [NotificationGatewayType::TELEGRAM],
			NotificationGatewayType::MAIL => [NotificationGatewayType::MAIL],
			NotificationGatewayType::WEBHOOK => [NotificationGatewayType::WEBHOOK],
			default => [],
		};
	}

	public function toTelegram(User $user): TelegramMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::TELEGRAM)
			->messageTriggerUsed(NotificationTriggerType::DEMO);

		return TelegramMessage::create()
			->content(__('notifications/telegram/demo.message'))
			->button(__('notifications/telegram/demo.button'),
				sprintf('%s/#/manage-notifications', config('app.url')));
	}

	public function toMail(User $user): MailMessage
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::MAIL)
			->messageTriggerUsed(NotificationTriggerType::DEMO);

		return (new MailMessage)
			->subject(sprintf('%s - %s', __('notifications/mail/demo.subject'), config('app.name')))
			->markdown('mail.notification.demo', [
				'url' => sprintf('%s/#/manage-notifications', config('app.url')),
			]);
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function toWebhook(User $user): WebhookCall
	{
		$this->statisticService
			->messageGatewayUsed(NotificationGatewayType::WEBHOOK)
			->messageTriggerUsed(NotificationTriggerType::DEMO);

		return WebhookCall::create()
			->url($user->routeNotificationForWebhook())
			->payload([
				'type' => NotificationTriggerType::DEMO,
				'data' => [
					'state'   => 'ok',
					'message' => 'webhook notification is setup correctly',
				],
			])->useSecret($user->id);
	}
}
