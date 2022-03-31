<?php

namespace App\Api\Controller;

use App\Enum\TwilioWebhookState;
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
			case TwilioWebhookState::SUCCESS:
				$this->succeeded();
				break;
			case TwilioWebhookState::RETRY:
				$this->retryCall();
				break;
			case TwilioWebhookState::FAILED:
				$this->callFailed();
				break;
			case TwilioWebhookState::NO_ANSWER:
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
