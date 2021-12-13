<?php

namespace App\ApiClient;

use App\Api\Exceptions\OceanApiException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class OceanApiClient
{
	protected ClientInterface $client;

	public function __construct()
	{
		$this->client = new Client([
			'base_uri'        => config('defichain_ocean.base_uri'),
			'timeout'         => 5,
			'connect_timeout' => 5,
		]);
	}

	/**
	 * @throws \App\Api\Exceptions\OceanApiException
	 */
	public function loadVaultsForPage(string $nextPage = ''): array
	{
		$path = strlen($nextPage) > 0
			? sprintf('%s?next=%s', config('defichain_ocean.vaults.get'), $nextPage)
			: config('defichain_ocean.vaults.get');

		try {
			$response = $this->client->get($path);
		} catch (GuzzleException $e) {
			throw OceanApiException::message('loadVaultsForPage', $e);
		}

		return json_decode($response->getBody()->getContents(), true);
	}

	/**
	 * @throws \App\Api\Exceptions\OceanApiException
	 */
	public function getVault(string $address): array
	{
		try {
			$response = $this->client->get(sprintf(config('defichain_ocean.vaults.id'), $address));
		} catch (GuzzleException $e) {
			throw OceanApiException::message('multiple_vault', $e);
		}

		return json_decode($response->getBody()->getContents(), true)['data'];
	}

	/**
	 * @throws \App\Api\Exceptions\OceanApiException
	 */
	public function getLoanSchemes(): array
	{
		try {
			$response = $this->client->get(config('defichain_ocean.loan_schemes.get'));
		} catch (GuzzleException $e) {
			throw OceanApiException::message('loan_scheme', $e);
		}

		return json_decode($response->getBody()->getContents(), true)['data'];
	}

	/**
	 * @throws \App\Api\Exceptions\OceanApiException
	 */
	public function getFixedIntervalPrices(): array
	{
		try {
			$response = $this->client->get(config('defichain_ocean.fixed_interval_prices.get'));
		} catch (GuzzleException $e) {
			throw OceanApiException::message('fixed_interval_prices', $e);
		}

		return json_decode($response->getBody()->getContents(), true)['data'];
	}
}
