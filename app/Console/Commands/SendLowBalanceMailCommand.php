<?php

namespace App\Console\Commands;

use App\Enum\NotificationGatewayType;
use App\Mail\LowAccountBalanceMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Mail;

class SendLowBalanceMailCommand extends Command
{
	protected $signature = 'credits:inform-low-balance {numCalls=3 : Below this call value, users will get informed about their balance}';
	protected $description = 'Send mailing to users with low balance';

	public function handle()
	{
		$callAmount = $this->argument('numCalls');
		$informBelowBalance = $callAmount * config('twilio.phone_call_cost');

		$this->info(sprintf('Selecting users with a balance below %s DFI (%s calls)', $informBelowBalance, $callAmount));

		$phoneUsers = $this->loadUsersWithPhoneGateway();
		$lowBalanceUsers = $this->filterUsersWithLowBalance($phoneUsers, $informBelowBalance);
		$this->withProgressBar($lowBalanceUsers, function (User $user) {
			Mail::to($user->setting->depositInfoMail)->send(new LowAccountBalanceMail($user, $user->accountBalance));
		});

		$this->info(sprintf('Send out mails to %s users', $lowBalanceUsers->count()));
	}

	protected function loadUsersWithPhoneGateway(): EloquentCollection
	{
		return User::whereHas('gateways', function ($query) {
			$query->where('type', NotificationGatewayType::PHONE);
		})->get();
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
}
