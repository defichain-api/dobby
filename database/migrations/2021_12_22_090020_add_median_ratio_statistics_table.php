<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMedianRatioStatisticsTable extends Migration
{
	public function up()
	{
		Schema::table('statistics', function (Blueprint $table) {
			$table->unsignedInteger('median_ratio')->after('avg_ratio')->default(0);
		});
	}

	public function down()
	{
		Schema::table('statistics', function (Blueprint $table) {
			$table->dropColumn('median_ratio');
		});
	}
}
