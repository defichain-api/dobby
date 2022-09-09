<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->float('inform_dusd_interest_rate', 6, 2)->default(0);
			$table->boolean('inform_dusd_interest_above')->default(false);
		});
	}

	public function down()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->dropColumn('inform_dusd_interest_rate');
			$table->dropColumn('inform_dusd_interest_above');
		});
	}
};
