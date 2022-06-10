<?php

namespace App\Jobs;

use App\ApiClient\PhoneCallService;
use App\Enum\NotificationGatewayType;
use App\Enum\PhoneCallState;
use App\Exceptions\NotificationGatewayException;
use App\Exceptions\PaymentException;
use App\Mail\NoCreditsAvailableMail;
use App\Models\PhoneCall;
use App\Models\Service\UserBalanceService;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class PhoneCallJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function __construct(
		public User          $user,
		public Vault         $vault,
		protected int        $retry = 0,
		protected ?PhoneCall $phoneCall = null
	) {
	}

	/**
	 * @throws NotificationGatewayException | \Throwable
	 */
	public function handle(PhoneCallService $service)
	{
		$phoneNumber = $this->user->gateways()->where('type', NotificationGatewayType::PHONE)->first()?->value;

		throw_if(is_null($phoneNumber), NotificationGatewayException::message(NotificationGatewayType::PHONE, sprintf
		('not available for user %s', $this->user->id)));

		$this->phoneCall = $this->phoneCall ?? PhoneCall::create([
				'userId'                 => $this->user->id,
				'vaultId'                => $this->vault->vaultId,
				'recipientNumber'        => $phoneNumber,
				'currentCollateralRatio' => $this->vault->collateralRatio,
				'nextCollateralRatio'    => $this->vault->nextCollateralRatio,
				'state'                  => PhoneCallState::INITIATED,
			]);

		// make payment
		try {
			app(UserBalanceService::class)
				->forUser($this->user)
				->payAmount(
					config('twilio.phone_call_cost'),
					sprintf('Trigger warning vault %s', $this->user->vaults->where('vaultId', $this->vault->vaultId)
							->first()?->pivot->name
						?? str_truncate_middle($this->vault->vaultId, 15)),
					$this->phoneCall
				);
			$service->initiateCall($this->phoneCall, $this->retry);
		} catch (PaymentException|\Throwable) {
			Mail::to($this->user->setting->depositInfoMail)->send(new NoCreditsAvailableMail($this->user));
			$this->phoneCall->update([
				'state' => PhoneCallState::FAILED_NO_CREDITS,
			]);
		}
	}
}
