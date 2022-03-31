<?php

namespace App\Channel;

use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class PhoneCallChannel
{
	protected Client $twilioClient;

	/**
	 * @throws \Twilio\Exceptions\ConfigurationException
	 */
	public function __construct()
	{
		$this->twilioClient = new Client(config('twilio.account_sid'), config('twilio.auth_token'));
	}

	public function validatePhoneNumber(string $phoneNumber, string $countryIso): array
	{
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
			'carrier'                   => [
				'type' => $result['carrier']['type'] ?? 'n/a',
				'name' => $result['carrier']['name'] ?? 'n/a',
			],
		];
	}

	public function initiateCall(): bool
	{
		try {
			$this->twilioClient->studio->v2->flows(config('twilio.main_flow_sid'))
				->executions
				->create('+4991239988404', config('twilio.phone_number'), [
					'parameters' => json_encode([
						'retry_count'   => 0, // set to 0 as it's the initial call
						'current_ratio' => 214,
						'next_ratio'    => 180,
						'dobby_key'     => '3280e083-5f56-49d8-80b9-a96933002120',
						'vault_id'      => '00307d6cd3bb2b888304f22cce7c93ae64a7c4f30487d92648f2155a13e293ee',
						'vault_name'    => 'Mein Vault',
						'language'      => 'de',
						'webhook_url'   => route('webhook-twilio'),
					]),
				]);

			return true;
		} catch (TwilioException $e) {
			ray($e);

			return false;
		}
	}
}
