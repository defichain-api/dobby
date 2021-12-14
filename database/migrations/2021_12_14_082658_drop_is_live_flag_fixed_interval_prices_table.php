<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropIsLiveFlagFixedIntervalPricesTable extends Migration
{
	public function up()
	{
		Schema::table('fixed_interval_prices', function (Blueprint $table) {
			$table->dropColumn('isLive');
			$table->unsignedInteger('blockHeight')->after('nextPrice');
		});
	}

	public function down()
	{
		Schema::table('fixed_interval_prices', function (Blueprint $table) {
			$table->boolean('isLive')->after('timestamp')->default(true);
			$table->dropColumn('blockHeight');
		});
	}
}
