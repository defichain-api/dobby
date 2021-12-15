<?php

namespace App\Listeners;

use App\Enum\QueueName;
use App\Enum\VaultStates;
use App\Events\VaultUpdatingStateEvent;
use App\Models\User;
use App\Models\Vault;
use App\Notifications\VaultFrozenNotification;
use App\Notifications\VaultInLiquidationNotification;
use App\Notifications\VaultMayLiquidateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class VaultUpdatingStateListener implements ShouldQueue
{
	public string $queue = QueueName::LISTENER_QUEUE;

	public function handle(VaultUpdatingStateEvent $event): void
	{
		$vault = $event->vault();
		if (!$this->vaultShouldNotifyUsers($vault)) {
			return;
		}

		$users = $vault->users;
		$users->each(function (User $user) use ($vault) {
			if ($vault->state === VaultStates::MAYLIQUIDATE) {
				$user->notify(new VaultMayLiquidateNotification($vault));
			} elseif ($vault->state === VaultStates::INLIQUIDATION) {
				$user->notify(new VaultInLiquidationNotification($vault));
			} elseif ($vault->state === VaultStates::FROZEN) {
				$user->notify(new VaultFrozenNotification($vault));
			}
		});
	}

	protected function vaultShouldNotifyUsers(Vault $vault): bool
	{
		return in_array($vault->state, [VaultStates::MAYLIQUIDATE, VaultStates::INLIQUIDATION, VaultStates::FROZEN]);
	}
}
