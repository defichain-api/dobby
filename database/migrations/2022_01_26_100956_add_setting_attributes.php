<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingAttributes extends Migration
{
	public function up()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->boolean('current_ratio_enabled')->default(true);
			$table->string('timezone')->default('UTC');

			$table->boolean('ui_privacy_enabled')->default(true);
			$table->boolean('ui_dashboard_healthSummary_enabled')->default(true);
			$table->boolean('ui_dashboard_collateralInfo_enabled')->default(true);
			$table->boolean('ui_dashboard_collateralWaypoints_enabled')->default(true);

			$table->renameColumn('theme', 'ui_theme');
		});
	}

	public function down()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->dropColumn('current_ratio_enabled');
			$table->dropColumn('timezone');
			$table->dropColumn('ui_privacy_enabled');
			$table->dropColumn('ui_dashboard_healthSummary_enabled');
			$table->dropColumn('ui_dashboard_collateralInfo_enabled');
			$table->dropColumn('ui_dashboard_collateralWaypoints_enabled');

			$table->renameColumn('ui_theme', 'theme');
		});
	}
}
