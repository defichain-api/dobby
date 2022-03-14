<?php

namespace App\Api\Service;

use App\Api\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\Vault;

class UserService
{
	public function update(UpdateUserRequest $request): bool
	{
		/** @var \App\Models\UserSetting $userSetting */
		$userSetting = $request->get('user')->setting
			?? UserSetting::create([
				'userId' => $request->get('user')->id,
			]);

		return $userSetting->update([
			'language'                                 => $request->hasLanguage() ? $request->language() : $userSetting->language,
			'ui_theme'                                 => $request->hasTheme() ? $request->theme() : $userSetting->ui_theme,
			'summary_interval'                         => $request->hasSummaryInterval() ? $request->summaryInterval() : $userSetting->summary_interval,
			'timezone'                                 => $request->hasTimezone() ? $request->timezone() : $userSetting->timezone,
			'ui_privacy_enabled'                       => $request->hasUiPrivacyEnabled() ? $request->uiPrivacyEnabled() : $userSetting->ui_privacy_enabled,
			'ui_dashboard_healthSummary_enabled'       => $request->hasUiDashboardHealthSummaryEnabled()
				? $request->uiDashboardHealthSummaryEnabled()
				: $userSetting->ui_dashboard_healthSummary_enabled,
			'ui_dashboard_collateralInfo_enabled'      => $request->hasUiDashboardCollateralInfoEnabled()
				? $request->uiDashboardCollateralInfoEnabled()
				: $userSetting->ui_dashboard_collateralInfo_enabled,
			'ui_dashboard_collateralWaypoints_enabled' => $request->hasUiDashboardCollateralWaypointsEnabled()
				? $request->uiDashboardCollateralWaypointsEnabled()
				: $userSetting->ui_dashboard_collateralWaypoints_enabled,
		]);
	}

	public function delete(User $user): bool
	{
		$user->gateways()->delete();
		$user->setting()->delete();
		$user->vaults()->each(function (Vault $vault) use ($user) {
			app(NotificationTriggerService::class)->deleteTriggerForUserVault($user, $vault->vaultId);
		});
		$user->vaults()->sync([]);

		return $user->delete();
	}
}
