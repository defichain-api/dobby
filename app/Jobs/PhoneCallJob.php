<?php

namespace App\Jobs;

use App\ApiClient\PhoneCallService;
use App\Enum\NotificationGatewayType;
use App\Enum\PhoneCallState;
use App\Exceptions\NotificationGatewayException;
use App\Models\PhoneCall;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PhoneCallJob implements ShouldQueue
{
	use Dispatchable, Queueable;

	public function __construct(protected User $user, protected Vault $vault, protected int $retry = 0)
	{
	}

	/**
	 * @throws NotificationGatewayException | \Throwable
	 */
	public function handle(PhoneCallService $service)
	{
		$phoneNumber = $this->user->gateways()->where('type', NotificationGatewayType::PHONE)->first()?->value;
		ray(sprintf('phone number: %s', $phoneNumber));
		throw_if(is_null($phoneNumber), NotificationGatewayException::message(NotificationGatewayType::PHONE, sprintf
		('not available for user %s', $this->user->id)));

		$phoneCall = PhoneCall::create([
			'userId'                 => $this->user->id,
			'vaultId'                => $this->vault->vaultId,
			'recipientNumber'        => $phoneNumber,
			'currentCollateralRatio' => $this->vault->collateralRatio,
			'nextCollateralRatio'    => $this->vault->nextCollateralRatio,
			'state'                  => PhoneCallState::INITIATED,
		]);
		ray($phoneCall);
		$service->initiateCall($phoneCall, $this->retry);
	}
}
