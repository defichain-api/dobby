<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePricesDecimalPlaces extends Migration
{
	public function up()
	{
		Schema::table('fixed_interval_prices', function (Blueprint $table) {
			$table->float('activePrice', 15, 8)->change();
			$table->float('nextPrice', 15, 8)->change();
		});
	}

	public function down()
	{
		Schema::table('fixed_interval_prices', function (Blueprint $table) {
			$table->float('activePrice', 10, 2)->change();
			$table->float('nextPrice', 10, 2)->change();
		});
	}
}
