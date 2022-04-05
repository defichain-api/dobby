<?php

namespace App\Api\Controller;

use App\Enum\PhoneCallState;
use App\Http\Requests\TwilioWebhookRequest;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Rest\Client;

class TwilioWebhookController
{
	protected Client $twilioClient;
	protected TwilioWebhookRequest $request;

	/**
	 * @throws \Twilio\Exceptions\ConfigurationException
	 */
	public function __construct()
	{
		$this->twilioClient = new Client(config('twilio.account_sid'), config('twilio.auth_token'));
	}

	public function __invoke(TwilioWebhookRequest $request): Response
	{
		$this->request = $request;
		switch ($request->status()) {
			case PhoneCallState::SUCCESS->value:
				$this->succeeded();
				break;
			case PhoneCallState::RETRY->value:
				$this->retryCall();
				break;
			case PhoneCallState::FAILED->value:
				$this->callFailed();
				break;
			case PhoneCallState::NO_ANSWER->value:
				$this->callNoAnswer();
				break;
			default:
				break;
		}

		return response()->json([
			'message' => 'ok',
		], Response::HTTP_OK);
	}

	protected function succeeded()
	{

	}

	protected function retryCall()
	{

	}

	protected function callFailed()
	{

	}

	protected function callNoAnswer()
	{

	}
}
