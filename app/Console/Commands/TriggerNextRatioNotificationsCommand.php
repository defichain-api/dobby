<?php

namespace App\Console\Commands;

use App\Enum\VaultStates;
use App\Models\NotificationTrigger;
use App\Notifications\VaultNextRatioNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class TriggerNextRatioNotificationsCommand extends Command
{
	protected $signature = 'notification:next-ratio';
	protected $description = 'Trigger Notifications based on the next ratio';

	public function handle()
	{
		$uniqueUserCollection = new Collection();
		$sendableTriggers = new Collection();

		NotificationTrigger::join('vaults', function ($query) {
			$query->on('notification_triggers.vaultId', '=', 'vaults.vaultId');
		})
			->whereRaw('ratio < nextCollateralRatio')
			->with(['gateways.user'])
			->where('state', VaultStates::ACTIVE)
			->where('nextCollateralRatio', '>', 0)
			->orderByDesc('ratio')
			->select('notification_triggers.*')
			->chunk(100, function (Collection $notificationTriggers) use (&$uniqueUserCollection, &$sendableTriggers) {
				$notificationTriggers->each(function (NotificationTrigger $trigger) use (
					&$uniqueUserCollection,
					&$sendableTriggers
				) {
					$elem = sprintf('%s_%s', $trigger->vaultId, $trigger->gateways()->first()->user->id);
					if (!$uniqueUserCollection->contains($elem)) {
						$uniqueUserCollection->add($elem);
						$sendableTriggers->add($trigger);
					}
				});
			});

		$sendableTriggers->each(function (NotificationTrigger $trigger) {
			$this->triggerNotifications($trigger);
		});
	}

	protected function triggerNotifications(NotificationTrigger $trigger): void
	{
		$vault = $trigger->vault;
		$user = $vault->users()->where('id', $trigger->gateways()->first()->user->id)->first();

		$trigger->notify(new VaultNextRatioNotification($vault, $user->pivot->name));
	}
}
