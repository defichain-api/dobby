<?php

namespace App\Console\Commands;

use App\Api\Exceptions\OceanApiException;
use App\ApiClient\OceanApiClient;
use App\Enum\NotificationGatewayType;
use App\Http\BotmanConversation\TelegramMessageService;
use App\Models\User;
use Illuminate\Console\Command;

class NotificationInterestRateCommand extends Command
{
	protected $signature = 'notification:dusd-interest-rate';
	protected $description = 'Send out notification for dUSD interest rate warning';

	public function handle(OceanApiClient $apiClient, TelegramMessageService $messageService)
	{
		try {
			$currentDusdInterestRate = $apiClient->loadDusdInterestRate();
		} catch (OceanApiException $e) {
			$message = sprintf('failed to load interest rate with message: %s', $e->getMessage());
			\Log::error($message);
			$this->error($message);

			return;
		}

		User::whereHas('setting', function ($query) use ($currentDusdInterestRate) {
			$query->where('inform_dusd_interest_rate', '>', $currentDusdInterestRate);
		})->with('gateways')->each(function (User $user) use ($messageService, $currentDusdInterestRate) {
			if (!$user->hasGateway(NotificationGatewayType::TELEGRAM)) {
				return true;
			}

			$messageService->sendWithUrlButton(
				__('notifications/telegram/dusd_interest_rate.message',
					['interest_rate' => $currentDusdInterestRate * 100]),
				$user->routeNotificationForTelegram(),
				'Visit Dobby Dashboard',
				config('app.url')
			);
		});
	}
}
