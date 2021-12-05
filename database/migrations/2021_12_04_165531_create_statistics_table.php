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
			$table->unsignedInteger('user_count')->default(0);
			$table->unsignedInteger('vault_count')->default(0);
			$table->unsignedInteger('sum_telegram_messages')->default(0);
			$table->unsignedInteger('sum_mail_messages')->default(0);
			$table->unsignedInteger('sum_webhook_messages')->default(0);
			$table->unsignedInteger('sum_info_notifications')->default(0);
			$table->unsignedInteger('sum_warning_notifications')->default(0);
			$table->unsignedInteger('sum_may_liquidate_notifications')->default(0);
			$table->unsignedInteger('sum_in_liquidation_notifications')->default(0);
			$table->unsignedInteger('sum_daily_messages')->default(0);
			$table->float('sum_collateral')->default(0);
			$table->float('sum_loan')->default(0);
			$table->float('avg_ratio')->default(0);
		});
	}

	public function down()
	{
		Schema::dropIfExists('statistics');
	}
}
