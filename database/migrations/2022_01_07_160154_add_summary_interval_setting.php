<?php

use App\Enum\SummaryInterval;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSummaryIntervalSetting extends Migration
{
	public function up()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->string('summary_interval')->after('theme')->default(SummaryInterval::DAILY_ONCE);
		});
	}

	public function down()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->dropColumn('summary_interval');
		});
	}
}
