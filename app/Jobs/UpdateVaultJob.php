<?php

namespace App\Jobs;

use App\Api\Service\VaultRepository;
use App\Api\Service\VaultService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateVaultJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function __construct(protected array $vaults) {}

	/**
	 * @throws \App\Api\Exceptions\DefichainApiException
	 */
	public function handle(VaultService $vaultService): void
	{
		$vaultService->updateVaults($this->vaults);
	}
}
