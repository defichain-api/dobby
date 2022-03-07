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

	public function validatePhoneNumber(string $phoneNumber)
	{
		try {
			$result = $this->twilioClient->lookups->v1->phoneNumbers($phoneNumber)
				->fetch(["countryCode" => "DE"]);
		} catch (TwilioException $e) {
			return $e;
		}

		return $result->toArray();
	}

	public function initiateCall(): bool
	{
		try {
			$this->twilioClient->studio->v2->flows(config('twilio.main_flow_sid'))
				->executions
				->create('+4991239988404', config('twilio.phone_number'), [
					'parameters' => json_encode([
						'current_ratio' => 214,
						'next_ratio'    => 180,
						'dobby_key'     => 'Pariatur',
						'vault_name'    => 'Mein Vault',
						'language'      => 'de',
					]),
				]);

			return true;
		} catch (TwilioException $e) {
			ray($e);

			return false;
		}
	}
}
