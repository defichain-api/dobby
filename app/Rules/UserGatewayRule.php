<?php

namespace App\Rules;

use App\Models\NotificationGateway;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UserGatewayRule implements Rule
{
	public function __construct(protected User $user) {}

	public function passes($attribute, $value): bool
	{
		return NotificationGateway::where('userId', $this->user->userId)
			->whereId($value)->count() === 1;
	}

	public function message(): string
	{
		return 'The gateway must be owned by the requesting user';
	}
}
