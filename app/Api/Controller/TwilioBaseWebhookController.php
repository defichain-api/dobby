<?php

namespace App\Api\Controller;

use Illuminate\Mail\Mailable;
use Mail;
use Twilio\Rest\Client;

class TwilioBaseWebhookController
{
	protected Client $twilioClient;

	/**
	 * @throws \Twilio\Exceptions\ConfigurationException
	 */
	public function __construct()
	{
		$this->twilioClient = new Client(config('twilio.account_sid'), config('twilio.auth_token'));
	}

	protected function sendMail(Mailable $mail, string $recipientMailAddress)
	{
		Mail::to($recipientMailAddress)->send($mail);
	}
}
