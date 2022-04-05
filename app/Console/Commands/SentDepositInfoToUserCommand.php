<?php

namespace App\Console\Commands;

use App\Mail\DepositInfoMail;
use App\Models\Deposit;
use Illuminate\Console\Command;
use Mail;

class SentDepositInfoToUserCommand extends Command
{
	protected $signature = 'info:deposit-mail';
	protected $description = 'Sent the "received deposit" info to the user';

	public function handle()
	{
		$this->withProgressBar(Deposit::where('sentInfoToUser', false)->get(),
			function (Deposit $deposit) {
				if (is_null($deposit->user) || is_null($deposit->user->setting->depositInfoMail)) {
					return true;
				}

				Mail::to($deposit->user->setting->depositInfoMail)
					->send(new DepositInfoMail($deposit));
				$deposit->update([
					'sentInfoToUser' => true,
				]);

				return true;
			});
	}
}
