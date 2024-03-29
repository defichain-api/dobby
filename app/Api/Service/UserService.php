<?php

namespace App\Api\Service;

use App\Api\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\Vault;
use Illuminate\Database\QueryException;

class UserService
{
	public function update(UpdateUserRequest $request): bool
	{
		/** @var \App\Models\UserSetting $userSetting */
		$userSetting = $request->get('user')->setting
			?? UserSetting::create([
				'userId' => $request->get('user')->id,
			]);
		try {
			return $userSetting->update([
				'language'                                 => $request->hasLanguage() ? $request->language() : $userSetting->language,
				'ui_theme'                                 => $request->hasTheme() ? $request->theme() : $userSetting->ui_theme,
				'depositFromAddress'                       => $request->hasDepositFromAddress() ? $request->depositFromAddress() : $userSetting->depositFromAddress,
				'depositInfoMail'                          => $request->hasDepositInfoMail() ? $request->depositInfoMail() : $userSetting->depositInfoMail,
				'summary_interval'                         => $request->hasSummaryInterval() ? $request->summaryInterval() : $userSetting->summary_interval,
				'timezone'                                 => $request->hasTimezone() ? $request->timezone() : $userSetting->timezone,
				'ui_privacy_enabled'                       => $request->hasUiPrivacyEnabled() ? $request->uiPrivacyEnabled() : $userSetting->ui_privacy_enabled,
				'inform_dusd_interest_rate_below'          => $request->hasInformDusdInterestRateBelow()
					? $request->informDusdInterestRateBelow()
					: $userSetting->inform_dusd_interest_rate_below,
				'inform_dusd_interest_rate_above'          => $request->hasInformDusdInterestRateAbove()
					? $request->informDusdInterestRateAbove()
					: $userSetting->inform_dusd_interest_rate_above,
				'inform_dusd_interest_rate'          => $request->hasDusdInterestRate()
					? $request->dusdInterestRate()
					: $userSetting->inform_dusd_interest_rate,
				'ui_dashboard_healthSummary_enabled'       => $request->hasUiDashboardHealthSummaryEnabled()
					? $request->uiDashboardHealthSummaryEnabled()
					: $userSetting->ui_dashboard_healthSummary_enabled,
				'ui_dashboard_collateralInfo_enabled'      => $request->hasUiDashboardCollateralInfoEnabled()
					? $request->uiDashboardCollateralInfoEnabled()
					: $userSetting->ui_dashboard_collateralInfo_enabled,
				'ui_dashboard_collateralWaypoints_enabled' => $request->hasUiDashboardCollateralWaypointsEnabled()
					? $request->uiDashboardCollateralWaypointsEnabled()
					: $userSetting->ui_dashboard_collateralWaypoints_enabled,
				'ui_dashboard_cards_carousel'              => $request->hasUiDashboardCardsAsCarousel()
					? $request->uiDashboardCardsAsCarousel()
					: $userSetting->ui_dashboard_cards_carousel,
			]);
		} catch (QueryException) {
			return false;
		}
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
