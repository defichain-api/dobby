<?php

namespace App\Console\Commands;

use App\Enum\NotificationGatewayType;
use App\Http\BotmanConversation\TelegramMessageService;
use App\Models\NotificationGateway;
use BotMan\BotMan\Exceptions\Base\BotManException;
use Illuminate\Console\Command;

class BroadcastMessageCommand extends Command
{
	protected $signature = 'broadcast:all-users {--testing}';
	protected $description = 'Send a message to all users, using the telegram gateway';

	public function handle(): void
	{
		$message = $this->ask('Enter the broadcast message now:');

		if (!$this->confirm('Sure you want to send out this message to all users?', false)) {
			$this->info('cancelling broadcasting message');

			return;
		}

		if ($this->option('testing')) {
			$this->warn('sending test message only to admin');
			$gateways = NotificationGateway::where('userId', config('app.admin_user_id'))
				->where('type', NotificationGatewayType::TELEGRAM)->get();
		} else {
			$gateways = NotificationGateway::where('type', NotificationGatewayType::TELEGRAM)->get();
		}
		$telegramMessageService = $this->telegramMessageService = new TelegramMessageService(app('botman'));

		$this->info('Sending out this message now:');
		$this->info($message);

		$this->withProgressBar($gateways,
			function (NotificationGateway $gateway) use ($telegramMessageService, $message) {
				$message = implode("\n", explode('\n', $message));
				try {
					$telegramMessageService->send($message, $gateway->value);
				} catch (BotManException $e) {
					$this->warn('was not able to send out message to %s', $gateway->value);
					$this->warn($e->getMessage());
				}
			});
	}
}
