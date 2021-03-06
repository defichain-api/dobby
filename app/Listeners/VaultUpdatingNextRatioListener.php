<?php

namespace App\Listeners;

use App\Enum\QueueName;
use App\Enum\VaultStates;
use App\Events\VaultUpdatingNextRatioEvent;
use App\Exceptions\NotificationTriggerNotAvailableException;
use App\Models\User;
use App\Notifications\VaultNextRatioNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class VaultUpdatingNextRatioListener implements ShouldQueue
{
	public string $queue = QueueName::LISTENER_QUEUE;

	public function handle(VaultUpdatingNextRatioEvent $event): void
	{
		$vault = $event->vault();
		if ($vault->nextCollateralRatio <= 0) {
			return;
		}

		// check ratio - send notifications
		$users = $vault->users;

		// abort if vault has no users tracking it
		if ($users->count() === 0) {
			return;
		}

		$users->each(function (User $user) use ($vault) {
			// cancel reporting if vault is not filled or not active
			if ($vault->nextCollateralRatio < 0 || !in_array($vault->state, VaultStates::ACTIVE_SEND_NOTIFICATIONS)) {
				return true;
			}
			try {
				$trigger = $user->nearestTriggerBelowRatio($vault, intval($vault->nextCollateralRatio));
			} catch (NotificationTriggerNotAvailableException) {
				return true;
			}

			$trigger->notify(new VaultNextRatioNotification($vault, $user->pivot->name));
		});
	}
}
