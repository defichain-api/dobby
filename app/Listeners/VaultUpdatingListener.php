<?php

namespace App\Listeners;

use App\Enum\NotificationTriggerType;
use App\Events\VaultUpdatingEvent;
use App\Exceptions\NotificationTriggerNotAvailableException;
use App\Models\User;
use App\Notifications\VaultInfoNotification;
use App\Notifications\VaultWarningNotification;

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
				ray('trigger INFO for user '.$user->id());
				$trigger->notify(new VaultInfoNotification($vault));
			} elseif ($trigger->type === NotificationTriggerType::WARNING) {
				ray('trigger WARNING for user '.$user->id());
				$trigger->notify(new VaultWarningNotification($vault));
			}
		});
	}
}
