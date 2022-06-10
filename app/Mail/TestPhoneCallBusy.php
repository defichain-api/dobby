<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestPhoneCallBusy extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	public function build(): self
	{
		return $this
			->subject(__('mail/testcall-busy.subject'))
			->markdown('mail.test-phone-call-busy');
	}
}
