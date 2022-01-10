<?php

namespace App\Api\Service;

use App\Api\Requests\SetupRequest;
use App\Api\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\Vault;

class UserService
{
	public function create(SetupRequest $request): User
	{
		$user = User::create();
		UserSetting::create([
			'userId'   => $user->id,
			'language' => $request->language(),
			'theme'    => $request->theme(),
		]);

		return $user;
	}

	public function update(UpdateUserRequest $request): bool
	{
		/** @var \App\Models\UserSetting $userSetting */
		$userSetting = $request->get('user')->setting
			?? UserSetting::create([
				'userId' => $request->get('user')->id,
			]);

		return $userSetting->update([
			'language'         => $request->hasLanguage() ? $request->language() : $userSetting->language,
			'theme'            => $request->hasTheme() ? $request->theme() : $userSetting->theme,
			'summary_interval' => $request->hasSummaryInterval() ? $request->summaryInterval() : $userSetting->summary_interval,
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
