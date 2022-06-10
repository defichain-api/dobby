<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoCreditsAvailableMail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	public function __construct(public User $dobbyUser)
	{
	}

	public function build(): self
	{
		return $this
			->subject(__('mail/no-credits-available.subject'))
			->markdown('mail.no-credits-available');
	}
}
