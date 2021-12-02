<?php

namespace App\Rules;

use App\Models\NotificationGateway;
use App\Models\NotificationTrigger;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UserTriggerRule implements Rule
{
	public function __construct(protected User $user, protected int $triggerId) {}

	public function passes($attribute, $value): bool
	{
		$trigger = NotificationTrigger::find($this->triggerId);

		return $trigger?->user()?->id === $this->user->id;
	}

	public function message(): string
	{
		return 'The trigger must be owned by the requesting user';
	}
}
