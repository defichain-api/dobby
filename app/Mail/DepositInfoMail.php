<?php

namespace App\Mail;

use App\Models\Deposit;
use App\Models\Service\UserBalanceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositInfoMail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	public function __construct(public Deposit $deposit)
	{
	}

	public function build(UserBalanceService $balanceService): self
	{
		return $this->markdown('mail.deposit-info', [
			'amount'          => $this->deposit->amountDfi,
			'balance'         => $balanceService->forUser($this->deposit->user())->accountBalance(),
			'phoneCallAmount' => floor($this->deposit->amountDfi / config('twilio.phone_call_cost')),
		]);
	}
}
