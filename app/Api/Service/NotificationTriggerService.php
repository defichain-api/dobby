<?php

namespace App\Api\Service;

use App\Api\Requests\CreateNotificationTriggerRequest;
use App\Api\Requests\DeleteNotificationTriggerRequest;
use App\Api\Requests\UpdateNotificationTriggerRequest;
use App\Models\NotificationTrigger;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotificationTriggerService
{
	public function create(CreateNotificationTriggerRequest $request): NotificationTrigger
	{
		$trigger = NotificationTrigger::create([
			'vaultId' => $request->vaultId(),
			'ratio'   => $request->ratio(),
		]);
		$trigger->gateways()->attach($request->gateways());

		return $trigger;
	}

	/**
	 * @throws \Throwable
	 */
	public function update(UpdateNotificationTriggerRequest $request): NotificationTrigger
	{
		$trigger = NotificationTrigger::whereId($request->triggerId())
			->first();
		$trigger->update([
			'ratio' => $request->ratio(),
		]);
		$trigger->gateways()->sync($request->gateways());

		return $trigger;
	}

	public function deleteTriggerForUserVault(User $user, string $vaultId): void
	{
		$trigger = $user->notificationTrigger()->where('vaultId', $vaultId);
		$trigger->each(function (NotificationTrigger $trigger) {
			$trigger->delete();
		});
	}

	public function delete(DeleteNotificationTriggerRequest $request): bool
	{
		try {
			$notificationTrigger = NotificationTrigger::with('gateways')->where('id', $request->triggerId())
				->firstOrFail();
		} catch (ModelNotFoundException) {
			return false;
		}

		if ($notificationTrigger->user()?->id !== $request->get('user')?->id) {
			return false;
		}

		return $notificationTrigger->delete();
	}
}
