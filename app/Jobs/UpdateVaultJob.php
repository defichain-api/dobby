<?php

namespace App\Jobs;

use App\Api\Service\VaultService;
use App\ApiClient\OceanApiClient;
use App\Enum\QueueName;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateVaultJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function __construct(protected string $nextPage = '') {}

	/**
	 * @throws \App\Api\Exceptions\OceanApiException
	 */
	public function handle(VaultService $vaultService): void
	{
		$data = app(OceanApiClient::class)->loadVaultsForPage($this->nextPage);
		$this->nextPage = $data['page']['next'] ?? 'n/a';
		if ($this->nextPage === 'n/a') {
			return;
		}
		$vaultService->updateVaults($data['data']);
		dispatch(new UpdateVaultJob($this->nextPage))
			->onQueue(QueueName::UPDATE_VAULTS_QUEUE);
	}
}
