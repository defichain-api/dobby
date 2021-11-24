<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceBaseToFixedIntervalPricesTable extends Migration
{
	public function up()
	{
		Schema::table('fixed_interval_prices', function (Blueprint $table) {
			$table->string('priceBase')->after('priceFeedId')->nullable()->index();
		});
	}

	public function down()
	{
		Schema::table('fixed_interval_prices', function (Blueprint $table) {
			$table->dropColumn('priceBase');
		});
	}
}
