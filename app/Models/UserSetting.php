<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
	public $timestamps = false;
	protected $fillable = [
		'userId',
		'language',
		'summary_interval',
		'depositFromAddress',
		'depositInfoMail',
		'current_ratio_enabled',
		'timezone',
		'ui_theme',
		'ui_privacy_enabled',
		'ui_dashboard_healthSummary_enabled',
		'ui_dashboard_collateralInfo_enabled',
		'ui_dashboard_collateralWaypoints_enabled',
		'free_testcall_available',
		'ui_dashboard_cards_carousel',
	];
	protected $hidden = [
		'id',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'userId', 'id');
	}
}
