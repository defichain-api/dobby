<?php

namespace Database\Factories;

use App\Enum\PhoneCallState;
use App\Models\PhoneCall;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class PhoneCallFactory extends Factory
{
	protected $model = PhoneCall::class;

	#[ArrayShape([
		'recipientNumber'        => "string",
		'currentCollateralRatio' => "float",
		'nextCollateralRatio'    => "float",
		'state'                  => "mixed",
		'userId'                 => "\Illuminate\Database\Eloquent\Factories\Factory",
		'vaultId'                => "mixed",
	])] public function definition(): array
	{
		return [
			'recipientNumber'        => $this->faker->word(),
			'currentCollateralRatio' => $this->faker->randomFloat(1, 150, 160),
			'nextCollateralRatio'    => $this->faker->randomFloat(1, 150, 160),
			'state'                  => \Arr::random(PhoneCallState::cases())->value,

			'userId'  => User::factory(),
			'vaultId' => Vault::first(),
		];
	}

	public function forUser(User $user): self
	{
		return $this->state([
			'userId' => $user->id,
		]);
	}

	public function forVault(Vault $vault): self
	{
		return $this->state([
			'vaultId' => $vault->vaultId,
		]);
	}
}
