<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\UserSetting */
class UserSettingResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'language'                              => $this->language,
			'summaryInterval'                       => $this->summary_interval,
			'currentRatioEnabled'                   => (bool) $this->current_ratio_enabled,
			'timezone'                              => $this->timezone,
			'uiPrivacyEnabled'                      => (bool) $this->ui_privacy_enabled,
			'uiDashboardHealthSummaryEnabled'       => (bool) $this->ui_dashboard_healthSummary_enabled,
			'uiDashboardCollateralInfoEnabled'      => (bool) $this->ui_dashboard_collateralInfo_enabled,
			'uiDashboardCollateralWaypointsEnabled' => (bool) $this->ui_dashboard_collateralWaypoints_enabled,
			'uiDashboardCardsAsCarouselEnabled'     => (bool) $this->ui_dashboard_cards_carousel_enabled,
			'uiTheme'                               => $this->ui_theme,
			'user'                                  => new UserResource($this->whenLoaded('user')),
		];
	}
}
