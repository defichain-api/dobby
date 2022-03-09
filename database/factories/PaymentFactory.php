<?php

namespace Database\Factories;

use App\Models\Payment;
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
		return [
			'reason'    => $this->faker->sentence(5),
			'amountDfi' => $this->faker->randomFloat(8, 0.1, 35),
			'userId'    => User::factory(),
		];
	}

	public function forUser(User $user): self
	{
		return $this->state([
			'userId' => $user->id,
		]);
	}

	public function withAmount(float $amount): self
	{
		return $this->state([
			'amountDfi' => $amount,
		]);
	}
}
