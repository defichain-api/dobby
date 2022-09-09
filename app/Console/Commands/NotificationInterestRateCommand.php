<?php

namespace App\Console\Commands;

use App\Api\Exceptions\OceanApiException;
use App\ApiClient\OceanApiClient;
use App\Enum\NotificationGatewayType;
use App\Http\BotmanConversation\TelegramMessageService;
use App\Models\User;
use App\Models\Vault;
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
			$query->where(function ($q) use ($currentDusdInterestRate) {
				$q->where('inform_dusd_interest_rate', '>', $currentDusdInterestRate)
					->where('inform_dusd_interest_above', true);
			})->orWhere(function ($q) use ($currentDusdInterestRate) {
				$q->where('inform_dusd_interest_rate', '<', $currentDusdInterestRate)
					->where('inform_dusd_interest_above', false);
			});
		})->with('gateways')->each(function (User $user) use ($messageService, $currentDusdInterestRate) {
			if (!$user->hasGateway(NotificationGatewayType::TELEGRAM)) {
				return true;
			}

			$vaultsWithDusdLoan = [];
			$user->vaults->each(function (Vault $vault) use (&$vaultsWithDusdLoan, $currentDusdInterestRate) {
				if (!$vault->hasTokenLoan('DUSD')) {
					return true;
				}

				$vaultsWithDusdLoan[] = __('notifications/telegram/dusd_interest_rate.single_vault_message', [
					'vault_name'         => $vault->pivot->name,
					'vault_id'           => str_truncate_middle($vault->vaultId, 10),
					'vault_deeplink'     => $vault->deeplink(),
					'dusd_loan_interest' => max($vault->loanScheme->interestRate + $currentDusdInterestRate, 0),
				]);
			});
			// cancel notification for user as he as no vaults with dUSD loan
			$message = __('notifications/telegram/dusd_interest_rate.message', [
				'interest_rate' => $currentDusdInterestRate,
			]);
			if (count($vaultsWithDusdLoan) > 0) {
				$message .= sprintf(
					"\r\n\r\n%s\r\n%s",
					__('notifications/telegram/dusd_interest_rate.vault_intro'),
					implode("\r\n", $vaultsWithDusdLoan)
				);
			}

			$messageService->sendWithUrlButton(
				$message,
				$user->routeNotificationForTelegram(),
				'Visit Dobby Dashboard',
				config('app.frontend_url')
			);
		});
	}
}
