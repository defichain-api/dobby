<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRetryCountToPhoneCallsTable extends Migration
{
	public function up()
	{
		Schema::table('phone_calls', function (Blueprint $table) {
			$table->smallInteger('retry_count')->default(0)->after('state');
		});
	}

	public function down()
	{
		Schema::table('phone_calls', function (Blueprint $table) {
			$table->dropColumn('retry_count');
		});
	}
}
