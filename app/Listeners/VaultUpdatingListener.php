<?php

namespace App\Listeners;

use App\Enum\NotificationTriggerType;
use App\Events\VaultUpdatingEvent;
use App\Exceptions\NotificationTriggerNotAvailableException;
use App\Models\User;
use App\Notifications\VaultInfoTriggerNotification;
use App\Notifications\VaultWarningTriggerNotification;

class VaultUpdatingListener
{
	public function handle(VaultUpdatingEvent $event)
	{
		// check ratio - send notifications
		$vault = $event->vault();
		$users = $vault->users;

		$users->each(function (User $user) use ($vault) {
			try {
				$trigger = $user->nearestTriggerBelowRatio($vault->collateralRatio);
			} catch (NotificationTriggerNotAvailableException) {
				return;
			}

			if ($trigger->type === NotificationTriggerType::INFO) {
				$trigger->notify(new VaultInfoTriggerNotification($vault));
			} elseif ($trigger->type === NotificationTriggerType::WARNING) {
				$trigger->notify(new VaultWarningTriggerNotification($vault));
			}
		});
	}
}
