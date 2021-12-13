<?php

namespace App\Api\Service;

use App\Api\Requests\SetupRequest;
use App\Api\Requests\UpdateUserRequest;
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

	public function update(UpdateUserRequest $request): bool
	{
		/** @var \App\Models\User $user */
		$user = $request->get('user');

		return $user->update([
			'language' => $request->hasLanguage() ? $request->language() : $user->language,
			'theme'    => $request->hasTheme() ? $request->theme() : $user->theme,
		]);
	}

	public function delete(User $user): bool
	{
		return $user->delete();
	}
}
