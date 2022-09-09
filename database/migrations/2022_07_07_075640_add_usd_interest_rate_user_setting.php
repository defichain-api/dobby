<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->float('inform_dusd_interest_rate_above', 6, 2)->default(null)->nullable();
			$table->float('inform_dusd_interest_rate_below', 6, 2)->default(null)->nullable();
			$table->boolean('inform_dusd_interest_rate')->default(false);
		});
	}

	public function down()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->dropColumn('inform_dusd_interest_rate_above');
			$table->dropColumn('inform_dusd_interest_rate_below');
			$table->dropColumn('inform_dusd_interest_rate');
		});
	}
};
