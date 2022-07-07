<?php

namespace App\ApiClient;

use App\Enum\NotificationGatewayType;
use App\Exceptions\PaymentException;
use App\Models\PhoneCall;
use App\Models\Service\UserBalanceService;
use App\Models\User;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use function cache;
use function config;
use function now;

class PhoneCallService
{
	protected Client $twilioClient;

	/**
	 * @throws \Twilio\Exceptions\ConfigurationException
	 */
	public function __construct()
	{
		$this->twilioClient = new Client(config('twilio.account_sid'), config('twilio.auth_token'));
	}

	public function validatePhoneNumber(string $phoneNumber, string $countryIso = 'de_DE'): array
	{
		return cache()->remember(
			sprintf('phone_number.%s', $phoneNumber),
			now()->addMinutes(5),
			function () use ($phoneNumber, $countryIso) {
				try {
					$result = $this->twilioClient->lookups->v1->phoneNumbers($phoneNumber)
						->fetch(['countryCode' => $countryIso])->toArray();
				} catch (TwilioException $e) {
					return [
						'isValid' => false,
						'message' => $e->getMessage(),
					];
				}

				return [
					'isValid'                   => true,
					'countryCode'               => $result['countryCode'],
					'nationalPhoneNumberFormat' => $result['nationalFormat'],
					'phoneNumber'               => $result['phoneNumber'],
				];
			});
	}

	public function initiateCall(PhoneCall $phoneCall, int $retryCount = 0): bool
	{
		try {
			$this->twilioClient->studio->v2->flows(config('twilio.main_flow_sid'))
				->executions
				->create($phoneCall->recipientNumber, config('twilio.phone_number'), [
					'parameters' => json_encode([
						'retry_count'   => $retryCount, // should start with 0 for the first call
						'current_ratio' => $phoneCall->currentCollateralRatio,
						'next_ratio'    => $phoneCall->nextCollateralRatio,
						'dobby_key'     => $phoneCall->userId,
						'vault_id'      => $phoneCall->vaultId,
						'vault_name'    => $phoneCall->vault->pivot->name ?? '',
						'language'      => $phoneCall->user?->setting?->language ?? config('app.locale'),
						'webhook_url'   => route('webhook-twilio'),
					]),
				]);

			return true;
		} catch (TwilioException $e) {
			return false;
		}
	}

	public function initiateTestCall(User $user): bool
	{
		$phoneNumber = $user->gateways()->where('type', NotificationGatewayType::PHONE)->first()?->value;

		try {
			$this->twilioClient->studio->v2->flows(config('twilio.testcall_flow_sid'))
				->executions
				->create($phoneNumber, config('twilio.phone_number'), [
					'parameters' => json_encode([
						'language'    => $user->setting?->language ?? config('app.locale'),
						'dobby_key'   => $user->id,
						'webhook_url' => route('webhook-testcall-twilio'),
					]),
				]);

			if (!$user->setting->free_testcall_available) {
				app(UserBalanceService::class)
					->forUser($user)
					->payAmount(config('twilio.phone_test_call_cost'), 'Test call');
			} else {
				$user->setting->update([
					'free_testcall_available' => false,
				]);
			}

			return true;
		} catch (TwilioException|PaymentException|\Throwable) {
			return false;
		}
	}
}
