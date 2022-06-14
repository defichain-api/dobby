<?php

namespace App\Console\Commands;

use App\Enum\VaultStates;
use App\Models\Vault;
use Illuminate\Console\Command;

class InactivateVaultsCommand extends Command
{
	protected $signature = 'update:inactivate-vaults';
	protected $description = 'Set vaults to state "inactive"';

	public function handle()
	{
		$this->info(sprintf('%s: starting deactivating vaults', now()->toDateTimeString()));
		// count vaults
		$vaultQuery = Vault::where(function ($query) {
			$query->where('collateralRatio', -1)
				->where('nextCollateralRatio', -1)
				->where('vaultId', 'NOT LIKE', '%demo%')
				->where('state', '!=', VaultStates::INACTIVE);
		})->orWhere(function ($query) {
			$query->where('updated_at', '<', now()->subHours(2))
				->where('vaultId', 'NOT LIKE', '%demo%')
				->where('state', '!=', VaultStates::INACTIVE);
		});

		$count = $vaultQuery->count();
		$vaultQuery->update([
			'state' => VaultStates::INACTIVE,
		]);

		$this->info(sprintf('inactivated %s vaults', $count));
		$this->info(sprintf('%s: ending deactivating vaults', now()->toDateTimeString()));
	}
}
