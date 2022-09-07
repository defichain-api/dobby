<?php

namespace App\Console\Commands;

use App\Enum\NotificationGatewayType;
use App\Exceptions\NotificationGatewayException;
use App\Http\BotmanConversation\TelegramMessageService;
use App\Mail\LowAccountBalanceMail;
use App\Models\Repository\NotificationGatewayRepository;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Mail;

class SendLowBalanceMailCommand extends Command
{
	protected $signature = 'credits:inform-low-balance {numCalls=2 : Below this call value, users will get informed about their balance}';
	protected $description = 'Send mailing to users with low balance';
	protected int $recommendedMinCallAmount;
	protected float $informBelowBalance;

	public function handle(
		NotificationGatewayRepository $gatewayRepository,
		TelegramMessageService        $telegramMessageService
	) {
		$this->recommendedMinCallAmount = $this->argument('numCalls');
		$this->informBelowBalance = $this->recommendedMinCallAmount * config('twilio.phone_call_cost');

		$this->info(sprintf('Selecting users with a balance below %s DFI (%s calls)', $this->informBelowBalance,
			$this->recommendedMinCallAmount));

		$phoneUsers = $this->loadUsersWithPhoneGateway();
		$lowBalanceUsers = $this->filterUsersWithLowBalance($phoneUsers);
		$this->withProgressBar($lowBalanceUsers,
			function (User $user) use ($gatewayRepository, $telegramMessageService) {
				if (isset($user->setting->depositInfoMail)) {
					Mail::to($user->setting->depositInfoMail)->send(new LowAccountBalanceMail($user,
						$user->accountBalance, $this->recommendedMinCallAmount));
				} else {
					\Log::info(sprintf('No deposit info mail set for user #%s, low balance mail sending failed',
						$user->id));
				}

				$this->sendTelegramIfAvailable($user, $gatewayRepository, $telegramMessageService);
			});

		$this->info(sprintf('Send out mails to %s users', $lowBalanceUsers->count()));
	}

	protected function loadUsersWithPhoneGateway(): EloquentCollection
	{
		return User::whereHas('gateways', function ($query) {
			$query->where('type', NotificationGatewayType::PHONE);
		})->with('gateways')->get();
	}

	protected function filterUsersWithLowBalance(EloquentCollection $users): Collection
	{
		$result = new Collection();
		$users->each(function (User $user) use (&$result) {
			$credits = $user->credits();
			if ($credits < $this->informBelowBalance) {
				$user->accountBalance = $credits;
				$result->add($user);
			}
		});

		return $result;
	}

	protected function sendTelegramIfAvailable(
		User                          $user,
		NotificationGatewayRepository $gatewayRepository,
		TelegramMessageService        $telegramMessageService
	): void {
		try {
			$telegramGateway = $gatewayRepository->telegram($user);
		} catch (NotificationGatewayException) {
			return;
		}

		$phoneCallAmount = $this->getPhoneCallAmount($user);
		$telegramMessageService->sendWithUrlButton(__('mail/low-account-balance.text',
			[
				'balance'                 => $user->accountBalance,
				'phoneCallAmount'         => $phoneCallAmount,
				'more_dfi'                => $this->informBelowBalance - $user->accountBalance,
				'recommended_call_amount' => $this->recommendedMinCallAmount,
			]), $telegramGateway->value, 'Deposit DFI now', config('app.frontend_url') . '/#/manage-phone-calls');
	}

	protected function getPhoneCallAmount(User $user): float
	{
		return floor($user->accountBalance / config('twilio.phone_call_cost'));
	}
}
