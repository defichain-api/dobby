<?php

namespace Database\Factories;

use App\Models\Deposit;
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
		'received_at'   => "\Illuminate\Support\Carbon",
	])] public function definition(): array
	{
		return [
			'txid'          => $this->faker->uuid(),
			'senderAddress' => $this->faker->uuid(),
			'block'         => $this->faker->randomNumber(6),
			'amountDfi'     => $this->faker->randomFloat(8, 0.1, 5),
			'received_at'   => now(),
		];
	}

	public function withSenderAddress(string $senderAddress): self
	{
		return $this->state([
			'senderAddress' => $senderAddress,
		]);
	}

	public function withAmount(float $amount): self
	{
		return $this->state([
			'amountDfi' => $amount,
		]);
	}
}
