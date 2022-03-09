<?php

namespace Database\Factories;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class DepositFactory extends Factory
{
	protected $model = Deposit::class;

	#[ArrayShape([
		'txid'          => "string",
		'senderAddress' => "string",
		'block'         => "int",
		'amountDfi'     => "float",
		'userId'        => "\Illuminate\Database\Eloquent\Factories\Factory",
	])]
	public function definition(): array
	{
		return [
			'txid'          => $this->faker->uuid(),
			'senderAddress' => $this->faker->uuid(),
			'block'         => $this->faker->randomNumber(6),
			'amountDfi'     => $this->faker->randomFloat(8, 0.1, 33),

			'userId' => User::factory(),
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
