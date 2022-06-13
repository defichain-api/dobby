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
	protected $signature = 'credits:inform-low-balance {numCalls=3 : Below this call value, users will get informed about their balance}';
	protected $description = 'Send mailing to users with low balance';

	public function handle(
		NotificationGatewayRepository $gatewayRepository,
		TelegramMessageService        $telegramMessageService
	) {
		$callAmount = $this->argument('numCalls');
		$informBelowBalance = $callAmount * config('twilio.phone_call_cost');

		$this->info(sprintf('Selecting users with a balance below %s DFI (%s calls)', $informBelowBalance,
			$callAmount));

		$phoneUsers = $this->loadUsersWithPhoneGateway();
		$lowBalanceUsers = $this->filterUsersWithLowBalance($phoneUsers, $informBelowBalance);
		$this->withProgressBar($lowBalanceUsers,
			function (User $user) use ($gatewayRepository, $telegramMessageService) {
				Mail::to($user->setting->depositInfoMail)->send(new LowAccountBalanceMail($user,
					$user->accountBalance));

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

	protected function filterUsersWithLowBalance(EloquentCollection $users, float $informBelowBalance): Collection
	{
		$result = new Collection();
		$users->each(function (User $user) use (&$result, $informBelowBalance) {
			$credits = $user->credits();
			if ($credits <= $informBelowBalance) {
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

		$telegramMessageService->sendWithUrlButton(__('mail/low-account-balance.text',
			[
				'balance'         => $user->accountBalance,
				'phoneCallAmount' => floor($user->accountBalance / config('twilio.phone_call_cost')),
			]), $telegramGateway->value, 'Deposit DFI now', config('app.url') . '/#/manage-phone-calls');
	}
}
