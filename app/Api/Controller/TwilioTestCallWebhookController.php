<?php

namespace App\Api\Controller;

use App\Enum\PhoneCallState;
use App\Http\Requests\TwilioTestcallWebhookRequest;
use App\Mail\TestPhoneCallBusy;
use App\Mail\TestPhoneCallFailed;
use App\Mail\TestPhoneCallNoAnswer;
use App\Models\Service\UserBalanceService;
use Illuminate\Mail\Mailable;
use Mail;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Rest\Client;

class TwilioTestCallWebhookController
{
	protected Client $twilioClient;

	/**
	 * @throws \Twilio\Exceptions\ConfigurationException
	 */
	public function __construct()
	{
		$this->twilioClient = new Client(config('twilio.account_sid'), config('twilio.auth_token'));
	}

	public function __invoke(TwilioTestcallWebhookRequest $request, UserBalanceService $balanceService): Response
	{
		$dobbyUser = $request->dobbyUser();
		$recipientMailAddress = $dobbyUser->setting?->depositInfoMail;

		if (is_null($recipientMailAddress)) {
			return response()->json([
				'message' => 'recipient mail address missing',
			], Response::HTTP_OK);
		}

		switch ($request->status()) {
			case PhoneCallState::FAILED->value:
				$balanceService
					->forUser($request->dobbyUser())
					->refundAmount(config('twilio.phone_test_call_cost'), 'Test Call Failed - wrong number?');

				$this->sendMail(new TestPhoneCallFailed(), $recipientMailAddress);
				break;
			case PhoneCallState::NO_ANSWER->value:
				$this->sendMail(new TestPhoneCallNoAnswer(), $recipientMailAddress);
				break;
			case PhoneCallState::BUSY->value:
				$this->sendMail(new TestPhoneCallBusy(), $recipientMailAddress);
				break;
			default:
				break;
		}

		return response()->json([
			'message' => 'ok',
		], Response::HTTP_OK);
	}

	protected function sendMail(Mailable $mail, string $recipientMailAddress)
	{
		Mail::to($recipientMailAddress)->send($mail);
	}

	protected function refundTestCallCosts(string $reason)
	{

	}
}
