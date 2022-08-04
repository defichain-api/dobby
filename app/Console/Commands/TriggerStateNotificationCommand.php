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
		$this->components->info(sprintf('%s: Start of sending out state notifications', now()->toDayDateTimeString()));

		Vault::whereIn('state', [
			VaultStates::MAYLIQUIDATE,
			VaultStates::INLIQUIDATION,
			VaultStates::FROZEN,
		])->chunk(100, function (Collection $vaults) {
			$vaults->each(function (Vault $vault) {
				$vault->users->each(function (User $user) use ($vault) {
					if ($vault->state === VaultStates::MAYLIQUIDATE) {
						if (!$this->checkCooldownPassed($user, $vault, VaultStates::MAYLIQUIDATE)) {
							return;
						}
						$this->logSendingNotification($vault, VaultStates::MAYLIQUIDATE);
						$user->notify(new VaultMayLiquidateNotification($vault, $user->pivot->name));
					} elseif ($vault->state === VaultStates::INLIQUIDATION) {
						if (!$this->checkCooldownPassed($user, $vault, VaultStates::INLIQUIDATION)) {
							return;
						}
						$this->logSendingNotification($vault, VaultStates::INLIQUIDATION);
						$user->notify(new VaultInLiquidationNotification($vault, $user->pivot->name));
					} elseif ($vault->state === VaultStates::FROZEN) {
						if (!$this->checkCooldownPassed($user, $vault, VaultStates::FROZEN)) {
							return;
						}
						$this->logSendingNotification($vault, VaultStates::FROZEN);
						$user->notify(new VaultFrozenNotification($vault, $user->pivot->name));
					}
				});
			});
		});

		$this->components->info(sprintf('%s: End of sending out state notifications', now()->toDayDateTimeString()));
	}

	protected function logSendingNotification(Vault $vault, string $vaultState): void
	{
		$this->components->task(sprintf(
			'sent state "%s" notification for vault %s',
			$vaultState,
			$vault->vaultId
		));
	}

	protected function checkCooldownPassed(User $user, Vault $vault, string $vaultState): bool
	{
		$cooldownIdentifier = sprintf('%s_%s', $vaultState, $vault->vaultId);
		if ($user->cooldown($cooldownIdentifier)->notPassed()) {
			$this->components->info(sprintf(
				'skipped vault %s state caused of cooldown (%s min rest time)',
				$vault->vaultId,
				$user->cooldown($cooldownIdentifier)->expiresAt()->diffInMinutes()
			));

			return false;
		}
		$user->cooldown($cooldownIdentifier)->until(now()->addMinutes(15));

		return true;
	}
}
