<?php

namespace App\Mail;

use App\Enum\QueueName;
use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositInfoMail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	public $queue = QueueName::NOTIFICATION_EMAIL_QUEUE;

	public function __construct(public Deposit $deposit)
	{
	}

	public function build(): self
	{
		return $this->with([
			'amount'          => $this->deposit->amountDfi,
			'sender_address'  => $this->deposit->senderAddress,
			'phoneCallAmount' => floor($this->deposit->amountDfi / config('twilio.phone_call_cost')),
		])->markdown('emails.deposit-info');
	}
}
