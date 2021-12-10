<?php

namespace App\Listeners;

use App\Enum\NotificationTriggerType;
use App\Enum\QueueName;
use App\Events\VaultUpdatingRatioEvent;
use App\Exceptions\NotificationTriggerNotAvailableException;
use App\Models\User;
use App\Notifications\VaultInfoTriggerNotification;
use App\Notifications\VaultWarningTriggerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class VaultUpdatingRatioListener implements ShouldQueue
{
	public string $queue = QueueName::LISTENER_QUEUE;

	public function handle(VaultUpdatingRatioEvent $event): void
	{
		$vault = $event->vault();
		if ($vault->collateralRatio <= 0) {
			return;
		}

		// check ratio - send notifications
		$users = $vault->users;

		$users->each(function (User $user) use ($vault) {
			// cancel reporting if vault is not filled
			if ($vault->collateralRatio < 0) {
				return true;
			}

			try {
				$trigger = $user->nearestTriggerBelowRatio($vault->collateralRatio);
			} catch (NotificationTriggerNotAvailableException) {
				return true;
			}

			if ($trigger->type === NotificationTriggerType::INFO) {
				$trigger->notify(new VaultInfoTriggerNotification($vault));
			} elseif ($trigger->type === NotificationTriggerType::WARNING) {
				$trigger->notify(new VaultWarningTriggerNotification($vault));
			}
		});
	}
}
