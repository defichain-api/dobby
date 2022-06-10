<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\PhoneCall;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class PaymentFactory extends Factory
{
	protected $model = Payment::class;

	#[ArrayShape([
		'reason'    => "string",
		'amountDfi' => "float",
		'userId'    => "\Illuminate\Database\Eloquent\Factories\Factory",
	])]
	public function definition(): array
	{
		$user = User::factory()->create();

		return [
			'reason'        => $this->faker->sentence(5),
			'amountDfi'     => $this->faker->randomFloat(4, 0.1, 1),
			'userId'        => $user,
			'phone_call_id' => PhoneCall::factory()->forUser($user),
		];
	}

	public function forUser(User $user): self
	{
		return $this->state([
			'userId'        => $user->id,
			'phone_call_id' => PhoneCall::factory()->forUser($user),
		]);
	}

	public function withAmount(float $amount): self
	{
		return $this->state([
			'amountDfi' => $amount,
		]);
	}
}
