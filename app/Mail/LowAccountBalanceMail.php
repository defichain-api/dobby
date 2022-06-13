<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowAccountBalanceMail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	public function __construct(protected User $user, protected float $accountBalance)
	{
	}

	public function build()
	{
		$callCosts = (float)config('twilio.phone_call_cost');
		$phoneCallAmount = floor($this->accountBalance / $callCosts);

		$this->markdown('mail.low-account-balance', [
			'balance'         => $this->accountBalance,
			'phoneCallAmount' => $phoneCallAmount,
			'phoneCallCost'   => $callCosts,
		])->subject(__('mail/low-account-balance.subject', ['phoneCallAmount' => $phoneCallAmount]));
	}
}
