<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveWarnColumnStatistics extends Migration
{
	public function up()
	{
		Schema::table('statistics', function (Blueprint $table) {
			$table->dropColumn('sum_warning_notifications');
			$table->renameColumn('sum_info_notifications', 'sum_trigger_notifications');
		});
	}

	public function down()
	{
		Schema::table('statistics', function (Blueprint $table) {
			$table->renameColumn('sum_trigger_notifications', 'sum_info_notifications');
			$table->unsignedInteger('sum_warning_notifications')->default(0);
		});
	}
}
