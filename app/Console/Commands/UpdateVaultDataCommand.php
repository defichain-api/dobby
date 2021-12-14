<?php

namespace App\Console\Commands;

use App\Api\Exceptions\OceanApiException;
use App\Api\Service\VaultService;
use App\ApiClient\OceanApiClient;
use Arr;
use Illuminate\Console\Command;

class UpdateVaultDataCommand extends Command
{
	protected $signature = 'update:vault_data {--queued}';
	protected $description = 'Update the vault data from ocean data source';

	public function handle(VaultService $vaultService): void
	{
		if ($this->option('queued')) {
			$this->info(sprintf('%s: starting the updating job with the queue', now()->toDateTimeString()));

//			dispatch(new UpdateVaultJob())->onQueue(QueueName::UPDATE_VAULTS_QUEUE);

			return;
		}
		$startTime = now();
		$this->info(sprintf('%s: vault update started', $startTime->toDateTimeString()));
		try {
			$this->vaultUpdateLoop($vaultService);
		} catch (OceanApiException $e) {
			\Log::error('updating vault failed', [
				'message' => $e->getMessage(),
				'line'    => $e->getLine(),
				'file'    => $e->getFile(),
			]);
		}
		$this->info(sprintf('%s: vault update ended', now()->toDateTimeString()));
		$this->newLine(2);
		$this->info(sprintf('time: %s sec (%s min)', $startTime->diffInSeconds(now()),
			$startTime->diffInMinutes(now())));
	}

	/**
	 * @throws \App\Api\Exceptions\OceanApiException
	 */
	protected function vaultUpdateLoop(VaultService $vaultService): void
	{
		$hasPages = true;
		$nextPage = '';
		$vaultCount = 0;

		while ($hasPages) {
			$data = app(OceanApiClient::class)->loadVaultsForPage($nextPage);
			$vaults = $data['data'] ?? [];
			$this->output->write('.', false);

			$vaultService->updateVaults($vaults);

			$vaultCount += count($vaults);
			if (!Arr::has($data, 'page.next')) {
				$hasPages = false;
				continue;
			}
			$nextPage = $data['page']['next'];
		}

		$this->newLine(2);
		$this->info(sprintf('updated %s vaults', $vaultCount));
	}
}
