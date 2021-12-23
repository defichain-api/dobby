<?php

namespace App\ApiClient;

use App\Api\Exceptions\DefichainApiException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class DefiChainApiClient implements BaseApiClient
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
				],
			]);
		} catch (GuzzleException $e) {
			throw DefichainApiException::message('multiple_vault', $e);
		}

		return json_decode($response->getBody()->getContents(), true)['data'];
	}

	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
	public function getLoanSchemes(): array
	{
		try {
			$response = $this->client->get(config('defichain_api.loan_schemes.get'));
		} catch (GuzzleException $e) {
			throw DefichainApiException::message('loan_scheme', $e);
		}

		return json_decode($response->getBody()->getContents(), true)['data'];
	}

	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
	public function getFixedIntervalPrices(): array
	{
		try {
			$response = $this->client->get(config('defichain_api.fixed_interval_prices.get'));
		} catch (GuzzleException $e) {
			throw DefichainApiException::message('fixed_interval_prices', $e);
		}

		return json_decode($response->getBody()->getContents(), true)['data'];
	}

	public function loadVaultsForPage(string $nextPage = ''): array
	{
		// TODO: Implement loadVaultsForPage() method.
		return [];
	}

	public function getVault(string $address): array
	{
		// TODO: Implement getVault() method.
		return [];
	}

	public function getStats(): array
	{
		// TODO: Implement getStats() method.
		return [];
	}

	public function currentBlockHeight(): int
	{
		// TODO: Implement currentBlockHeight() method.
		return -1;
	}
}
