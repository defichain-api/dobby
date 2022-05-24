<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PhoneCallNoAnswer extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	public function __construct(public User $dobbyUser)
	{}

	public function build(): self
	{
		return $this
			->subject(__('mail/call-no_answer.subject'))
			->markdown('mail.phone-call-no-answer');
	}
}
