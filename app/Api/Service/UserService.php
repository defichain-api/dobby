<?php

namespace App\Api\Service;

use App\Api\Requests\SetupRequest;
use App\Models\User;
use App\Models\Vault;

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
		$user->gateways()->delete();
		$user->vaults()->each(function (Vault $vault) use ($user) {
			app(NotificationTriggerService::class)->deleteTriggerForUserVault($user, $vault->vaultId);
		});
		$user->vaults()->sync([]);

		return $user->delete();
	}
}
