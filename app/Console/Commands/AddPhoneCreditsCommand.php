<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Service\UserBalanceService;
use App\Models\User;
use Illuminate\Console\Command;

class AddPhoneCreditsCommand extends Command
{
	protected $signature = 'credits:add';
	protected $description = 'Add (phone) credits for a single user';

	public function handle(UserBalanceService $balanceService)
	{
		$user = User::find($this->ask('Enter the dobby key:'));
		$amount = (float) $this->ask('Enter the amount of DFI to credit to user balance:');
		$reason = $this->ask('What\'s the reason for the credits?');

		if (is_null($user)) {
			$this->error('Dobby Key not found!');

			return;
		}

		Payment::create([
			'userId'    => $user->id,
			'amountDfi' => -$amount,
			'reason'    => $reason,
		]);
		$this->info(sprintf('Credit over %s DFI successful', $amount));
		$this->info(sprintf('current total balance: %s DFI', $balanceService->forUser($user)->accountBalance()));
	}
}
