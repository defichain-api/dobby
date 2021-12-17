<?php

namespace App\Console\Commands;

use App\Enum\VaultStates;
use App\Models\Vault;
use Illuminate\Console\Command;

class InactivateVaultsCommand extends Command
{
	protected $signature = 'update:inactivate-vaults {--age=1}';
	protected $description = 'Set vaults to state "inactive" if no update was received for >1h (param: --age=)';

	public function handle()
	{
		$maxAge = $this->option('age');
		$this->info(sprintf('%s: starting deactivating vaults', now()->toDateTimeString()));
		// count vaults
		$vaultQuery = Vault::where('updated_at', '<', now()->subHours($maxAge));
		$count = $vaultQuery->count();
		$vaultQuery->update([
			'state' => VaultStates::INACTIVE,
		]);

		$this->info(sprintf('updated %s vaults without an update for %s hours.', $count, $maxAge));
		$this->info(sprintf('%s: ending deactivating vaults', now()->toDateTimeString()));
	}
}
