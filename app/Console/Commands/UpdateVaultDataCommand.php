<?php

namespace App\Console\Commands;

use App\Enum\QueueName;
use App\Jobs\UpdateVaultJob;
use App\Models\Vault;
use Arr;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdateVaultDataCommand extends Command
{
	protected $signature = 'update:vault_data {--max=100}';
	protected $description = 'Update the vault data';

	public function handle(): void
	{
		$max = $this->option('max');
		if ($max < 1 || $max > 1000) {
			$this->warn('max has to be between 1..1000. Is set now to default value 100');
			$max = 100;
		}
		Vault::select('vaultId')
			->chunk($max, function (Collection $vaults) {
//				dispatch(new UpdateVaultJob(Arr::flatten($vaults->toArray())))
//					->onQueue(QueueName::UPDATE_VAULTS);
			});
	}
}
