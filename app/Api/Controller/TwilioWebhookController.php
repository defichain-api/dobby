<?php

namespace App\Api\Controller;

use App\Enum\PhoneCallState;
use App\Enum\QueueName;
use App\Http\Requests\TwilioWebhookRequest;
use App\Jobs\PhoneCallJob;
use App\Mail\PhoneCallFailed;
use App\Mail\PhoneCallNoAnswer;
use App\Models\PhoneCall;
use App\Models\PhoneWebhook;
use App\Models\Service\UserBalanceService;
use Symfony\Component\HttpFoundation\Response;

class TwilioWebhookController extends TwilioBaseWebhookController
{
	protected TwilioWebhookRequest $request;
	protected PhoneCall $phoneCall;

	public function __invoke(TwilioWebhookRequest $request): Response
	{
		$this->request = $request;
		$this->phoneCall = $this->getLatestCall($request);
		$this->storeWebhook($request);
		switch ($request->status()) {
			case PhoneCallState::SUCCESS->value:
				$this->succeeded();
				break;
			case PhoneCallState::RETRY->value:
				$this->retryCall($request->retryCount() + 1);
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

	/**
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	protected function getLatestCall(TwilioWebhookRequest $request): PhoneCall
	{
		return PhoneCall::where('userId', $request->dobbyKey())
			->where('vaultId', $request->vaultId())
			->latest()
			->firstOrFail();
	}

	protected function succeeded()
	{
		$this->setPhoneCallState(PhoneCallState::SUCCESS);
	}

	protected function retryCall(int $retryCount)
	{
		$this->setPhoneCallState(PhoneCallState::RETRY);
		$this->phoneCall->update([
			'retry_count' => $retryCount,
		]);
		PhoneCallJob::dispatch($this->request->dobbyUser(), $this->request->vault(), $retryCount, $this->phoneCall)
			->onQueue(QueueName::NOTIFICATION_PHONE_QUEUE)
			->delay(now()->addMinutes(2));
	}

	protected function callFailed()
	{
		$this->setPhoneCallState(PhoneCallState::FAILED);
		$dobbyUser = $this->request->dobbyUser();
		$this->sendMail(new PhoneCallFailed($dobbyUser), $dobbyUser->setting?->depositInfoMail);
		$this->refundCallCosts(0.6);
	}

	protected function callNoAnswer()
	{
		$this->setPhoneCallState(PhoneCallState::NO_ANSWER);
		$dobbyUser = $this->request->dobbyUser();
		$this->sendMail(new PhoneCallNoAnswer($dobbyUser), $dobbyUser->setting?->depositInfoMail);
		$this->refundCallCosts(0.5);
	}

	protected function setPhoneCallState(PhoneCallState $state): bool
	{
		return $this->phoneCall->update([
			'state' => $state->value,
		]);
	}

	protected function refundCallCosts(float $refundFactor = 0.5): void
	{
		app(UserBalanceService::class)
			->forUser($this->phoneCall->user)
			->refundAmount(
				config('twilio.phone_call_cost') * $refundFactor,
				'Call was not answered'
			);
	}

	protected function storeWebhook(TwilioWebhookRequest $request): void
	{
		PhoneWebhook::create([
			'phone_call_id' => $this->phoneCall->id,
			'state'         => $request->status(),
			'payload'       => $request->validated(),
		]);
	}
}
