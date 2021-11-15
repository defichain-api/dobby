<?php

namespace App\Api\Service;

use App\Api\Requests\CreateNotificationTriggerRequest;
use App\Api\Requests\UpdateNotificationTriggerRequest;
use App\Models\NotificationTrigger;
use Exception;

class NotificationTriggerService
{
	public function create(CreateNotificationTriggerRequest $request): NotificationTrigger
	{
		$trigger = NotificationTrigger::create([
			'vaultId' => $request->vaultId(),
			'ratio'   => $request->ratio(),
			'type'    => $request->type(),
		]);
		$trigger->gateways()->attach($request->gateways());

		return $trigger;
	}

	/**
	 * @throws \Throwable
	 */
	public function update(UpdateNotificationTriggerRequest $request): NotificationTrigger
	{
		$trigger = NotificationTrigger::where('vaultId', $request->vaultId())
			->first();
		$trigger->update([
			'ratio' => $request->ratio(),
			'type'  => $request->type(),
		]);
		$trigger->gateways()->sync($request->gateways());

		return $trigger;
	}
}
