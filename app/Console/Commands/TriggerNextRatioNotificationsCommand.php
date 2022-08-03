<?php

namespace App\Console\Commands;

use App\Enum\CooldownTypes;
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
		$this->components->info(sprintf('%s: Start of sending out notifications', now()->toDayDateTimeString()));
		$uniqueUserCollection = new Collection();
		$sendableTriggers = new Collection();

		NotificationTrigger::join('vaults', function ($query) {
			$query->on('notification_triggers.vaultId', '=', 'vaults.vaultId')
				->where('notification_triggers.ratio', '>=', 'vaults.nextCollateralRatio');
		})
			->with(['gateways.user'])
			->with('vault')
			->where('vaults.state', VaultStates::ACTIVE)
			->where('vaults.nextCollateralRatio', '>', 0)
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
			if ($trigger->ratio != 0 && $trigger->ratio >= $trigger->vault->nextCollateralRatio) {
				$this->components->info(sprintf('skip vault %s caused of ratio', $trigger->vault->vaultId));

				return;
			}
			$gatewayType = $trigger->gateways()->first()->type;

			if ($trigger->cooldown(CooldownTypes::getType($gatewayType))->notPassed()) {
				$this->components->info(sprintf(
					'skip vault %s caused of cooldown (%s rest time)',
					$trigger->vault->vaultId,
					$trigger->cooldown(CooldownTypes::getType($gatewayType))->expiresAt()->diffInMinutes()
				));

				return;
			}

			// mute notification for 15min
			$trigger->cooldown(CooldownTypes::getType($gatewayType))->until(now()->addMinutes(15));
			$this->triggerNotifications($trigger, $gatewayType);
		});

		$this->components->info(sprintf('%s: End of sending out notifications', now()->toDayDateTimeString()));
	}

	protected function triggerNotifications(NotificationTrigger $trigger, string $gatewayType): void
	{
		$vault = $trigger->vault;
		$user = $vault->users()->where('id', $trigger->gateways()->first()->user->id)->first();

		$trigger->notify(new VaultNextRatioNotification($vault, $user->pivot->name));

		$this->components->task(sprintf(
				'notification (%s) - vault %s, next/trigger ratio %s/%s',
				$gatewayType,
				$trigger->vault->vaultId,
				$trigger->vault->nextCollateralRatio,
				$trigger->ratio
			)
		);
	}
}
