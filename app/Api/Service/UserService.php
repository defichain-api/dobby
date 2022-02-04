<?php

namespace App\Api\Service;

use App\Api\Requests\SetupRequest;
use App\Api\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Vault;

class UserService
{
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
		$user->gateways()->delete();
		$user->vaults()->each(function (Vault $vault) use ($user) {
			app(NotificationTriggerService::class)->deleteTriggerForUserVault($user, $vault->vaultId);
		});
		$user->vaults()->sync([]);

		return $user->delete();
	}
}
