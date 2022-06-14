<?php

namespace App\Console\Commands;

use App\Enum\VaultStates;
use App\Models\User;
use App\Models\Vault;
use App\Notifications\VaultFrozenNotification;
use App\Notifications\VaultInLiquidationNotification;
use App\Notifications\VaultMayLiquidateNotification;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class TriggerStateNotificationCommand extends Command
{
	protected $signature = 'notification:vault-state';
	protected $description = 'Trigger Notifications based on the vault state';

	public function handle()
	{
		Vault::whereIn('state', [
			VaultStates::MAYLIQUIDATE,
			VaultStates::INLIQUIDATION,
			VaultStates::FROZEN,
		])->chunk(100, function (Collection $vaults){
			$vaults->each(function(Vault $vault){
				$vault->users->each(function (User $user) use ($vault){
					if ($vault->state === VaultStates::MAYLIQUIDATE) {
						$user->notify(new VaultMayLiquidateNotification($vault, $user->pivot->name));
					} elseif ($vault->state === VaultStates::INLIQUIDATION) {
						$user->notify(new VaultInLiquidationNotification($vault, $user->pivot->name));
					} elseif ($vault->state === VaultStates::FROZEN) {
						$user->notify(new VaultFrozenNotification($vault, $user->pivot->name));
					}
				});
			});
		});
	}
}
