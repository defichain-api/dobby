<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCarouselUserSettingsTable extends Migration
{
	public function up()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->dropColumn('ui_dashboard_cards_carousel_enabled');
			$table->string('ui_dashboard_cards_carousel', 15)->default('auto');
		});
	}

	public function down()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->boolean('ui_dashboard_cards_carousel_enabled')->default(false);
			$table->dropColumn('ui_dashboard_cards_carousel');
		});
	}
}
