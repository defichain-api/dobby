<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneStatisticsTable extends Migration
{
	public function up()
	{
		Schema::table('statistics', function (Blueprint $table) {
			$table->unsignedInteger('sum_phone_messages')->default(0)->after('sum_webhook_messages');
		});
	}

	public function down()
	{
		Schema::table('statistics', function (Blueprint $table) {
			$table->dropColumn('sum_phone_messages');
		});
	}
}
