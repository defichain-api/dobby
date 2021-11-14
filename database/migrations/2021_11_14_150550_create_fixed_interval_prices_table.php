<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixedIntervalPricesTable extends Migration
{
	public function up()
	{
		Schema::create('fixed_interval_prices', function (Blueprint $table) {
			$table->string('priceFeedId')->primary();
			$table->float('activePrice', 10, 2);
			$table->float('nextPrice', 10, 2);
			$table->timestamp('timestamp');
			$table->boolean('isLive')->default(false);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('fixed_interval_prices');
	}
}
