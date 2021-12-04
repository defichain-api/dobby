<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsTable extends Migration
{
	public function up()
	{
		Schema::create('statistics', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->date('date')->index();
			$table->integer('user_count')->default(0);
			$table->integer('vault_count')->default(0);
			$table->json('messages')->default('[]');
			$table->float('sum_collateral')->default(0);
			$table->float('sum_loan')->default(0);
		});
	}

	public function down()
	{
		Schema::dropIfExists('statistics');
	}
}
