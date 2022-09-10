<?php

namespace App\Console\Commands;

use App\Enum\CooldownTypes;
use App\Enum\NotificationGatewayType;
use App\Enum\VaultStates;
use App\Models\NotificationTrigger;
use App\Notifications\VaultNextRatioNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class TriggerNextRatioNotificationsCommand extends Command
{
	protected $signature = 'notification:vault-ratio';
	protected $description = 'Trigger Notifications based on the next ratio';

	public function handle()
	{
		$this->components->info(sprintf('%s: Start of sending out ratio notifications', now()->toDayDateTimeString()));
		$uniqueUserCollection = new Collection();
		$sendableTriggers = new Collection();

		NotificationTrigger::join('vaults', function ($join) {
			$join->on('notification_triggers.vaultId', '=', 'vaults.vaultId')
				->whereRaw('notification_triggers.ratio >= vaults.nextCollateralRatio');
		})
			->with(['gateways.user'])
			->with('vault')
			->whereNotIn('vaults.state', [VaultStates::INACTIVE, VaultStates::FROZEN])
			->where('vaults.nextCollateralRatio', '>', 0)
			->orderBy('ratio')
			->select('notification_triggers.*')
			->chunk(100, function (Collection $notificationTriggers) use (&$uniqueUserCollection, &$sendableTriggers) {
				$notificationTriggers->each(function (NotificationTrigger $trigger) use (
					&$uniqueUserCollection,
					&$sendableTriggers
				) {
					if ($trigger->gateways()->count() == 0) {
						return;
					}
					$elem = sprintf('%s_%s', $trigger->vaultId, $trigger->gateways()->first()->user->id);
					if (!$uniqueUserCollection->contains($elem)) {
						$uniqueUserCollection->add($elem);
						$sendableTriggers->add($trigger);
					}
				});
			});
		$sendableTriggers->each(function (NotificationTrigger $trigger) {
			$gatewayType = $trigger->gateways()->first()->type;

			if ($gatewayType != NotificationGatewayType::WEBHOOK
				&& $trigger->cooldown(CooldownTypes::getType($gatewayType))->notPassed()) {
				$this->components->info(sprintf(
					'skip vault %s caused of cooldown (%s min rest time)',
					$trigger->vaultId,
					$trigger->cooldown(CooldownTypes::getType($gatewayType))->expiresAt()->diffInMinutes()
				));

				return;
			}

			// mute notification for 15min
			$trigger->cooldown(CooldownTypes::getType($gatewayType))->until(now()->addMinutes(15));
			$this->triggerNotifications($trigger, $gatewayType);
		});

		$this->components->info(sprintf('%s: End of sending out ratio notifications', now()->toDayDateTimeString()));
	}

	protected function triggerNotifications(NotificationTrigger $trigger, string $gatewayType): void
	{
		$vault = $trigger->vault;
		if ($trigger->gateways()->first()->count() == 0 || is_null($vault) || $vault->users()->count() == 0) {
			return;
		}
		$user = $vault->users()->where('id', $trigger->gateways()->first()->user->id)->first();

		try {
			$trigger->notify(new VaultNextRatioNotification($vault, $user->pivot->name));
		} catch (\Exception|\Throwable $e) {
			\Log::error('notification not sent', [
				'vault'     => $vault->vault_id,
				'user'      => $user?->id,
				'exception' => [
					'message' => $e->getMessage(),
					'code'    => $e->getCode(),
				],
			]);
		}

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
