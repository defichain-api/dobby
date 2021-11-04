<?php

namespace App\ApiClient;

use App\Api\Exceptions\DefichainApiException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class DefiChainApiClient
{
	protected ClientInterface $client;

	public function __construct()
	{
		$this->client = new Client([
			'base_uri'        => config('defichain_api.base_uri'),
			'timeout'         => 5,
			'connect_timeout' => 5,
		]);
	}

	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
	public function getMultipleVaults(array $addresses): array
	{
		try {
			$response = $this->client->post(config('defichain_api.vaults.multiple'), [
				'form_params' => [
					'addresses' => $addresses,
				]
			]);
		} catch (GuzzleException $e) {
			throw DefichainApiException::message('multiple_vault', $e);
		}

		return json_decode($response->getBody()->getContents(), true);
	}
}
