<?php

namespace App\Api\Service;

use App\Api\Requests\SetupRequest;
use App\Models\User;

class UserService
{
	public function createUser(SetupRequest $request): User
	{
		return User::create([
			'language' => $request->getLanguage(),
			'theme'    => $request->getTheme(),
		]);
	}

	public function deleteUser(User $user): bool
	{
		return $user->delete();
	}
}
