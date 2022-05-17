<?php

namespace App\Console\Commands;

use App\Enum\QueueName;
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
				$depositInfoMail = $deposit->user()->setting->depositInfoMail;
				if (is_null($deposit->user()) || is_null($depositInfoMail)) {
					return true;
				}

				Mail::to($depositInfoMail)
					->queue((new DepositInfoMail($deposit))->onQueue(QueueName::NOTIFICATION_EMAIL_QUEUE));
				$deposit->update([
					'sentInfoToUser' => true,
				]);

				return true;
			});
	}
}
