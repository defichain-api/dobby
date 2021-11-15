<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToNotificationTriggersTable extends Migration
{
	public function up()
	{
		Schema::table('notification_triggers', function (Blueprint $table) {
			$table->string('type')->after('ratio')->default('info');
		});
	}

	public function down()
	{
		Schema::table('notification_triggers', function (Blueprint $table) {
			$table->dropColumn('type');
		});
	}
}
