<?php

namespace App\Api\Service;

use App\Api\Requests\SetupRequest;
use App\Models\User;

class UserService
{
	public function create(SetupRequest $request): User
	{
		return User::create([
			'language' => $request->language(),
			'theme'    => $request->theme(),
		]);
	}

	public function delete(User $user): bool
	{
		return $user->delete();
	}
}
