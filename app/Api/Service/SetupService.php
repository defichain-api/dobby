<?php

namespace App\Api\Service;

use App\Api\Requests\SetupRequest;
use App\Models\User;

class SetupService
{
	public function createUser(SetupRequest $request): User
	{
		return User::create([
			'language' => $request->getLanguage(),
			'theme'    => $request->getTheme(),
		]);
	}
}
