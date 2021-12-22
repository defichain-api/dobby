<?php

namespace App\ApiClient;

interface BaseApiClient
{
	public function loadVaultsForPage(string $nextPage = ''): array;

	public function getVault(string $address): array;

	public function getLoanSchemes(): array;

	public function getFixedIntervalPrices(): array;

	public function getStats(): array;

	public function currentBlockHeight(): int;
}
