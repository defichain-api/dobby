<?php

namespace App\Models\Service;

use App\Exceptions\PaymentException;
use App\Models\Payment;
use App\Models\User;

class UserBalanceService
{
	protected ?User $user;

	public function __construct(?User $user)
	{
		if ($user) {
			$this->forUser($user);
		}
	}

	public function forUser(User $user): self
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * @throws PaymentException|\Throwable
	 */
	public function payAmount(float $amount, ?string $reason): bool
	{
		throw_if($this->canNotPayAmount($amount), PaymentException::message(amount: $amount, user: $this->user));

		try {
			Payment::create([
				'userId'    => $this->user->id,
				'amountDfi' => $amount,
				'reason'    => $reason,
			]);

			return true;
		} catch (\Exception) {
			return false;
		}
	}

	public function canPayAmount(float $amount): bool
	{
		return $this->accountBalance() >= $amount;
	}

	public function canNotPayAmount(float $amount): bool
	{
		return $this->accountBalance() < $amount;
	}

	public function accountBalance(): float
	{
		return $this->user->credits();
	}
}
