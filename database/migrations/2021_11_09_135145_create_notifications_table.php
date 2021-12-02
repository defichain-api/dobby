<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
	public function up()
	{
		Schema::create('notification_triggers', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('vaultId')->index();
			$table->unsignedInteger('ratio')->default(0)->index();

			$table->foreign('vaultId')
				->references('vaultId')
				->on('vaults');
		});

		Schema::create('notification_gateways', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('userId');
			$table->string('type')->index();
			$table->string('value');

			$table->foreign('userId')
				->references('id')
				->on('users');
		});
	}

	public function down()
	{
		Schema::dropIfExists('notification_triggers');
		Schema::dropIfExists('notification_gateways');
	}
}
