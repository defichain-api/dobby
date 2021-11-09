<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationGatewayTriggerTable extends Migration
{
	public function up()
	{
		Schema::create('notification_gateway_trigger', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('gatewayId');
			$table->unsignedBigInteger('triggerId');

			$table->foreign('gatewayId')
				->references('id')
				->on('notification_gateways')
				->cascadeOnDelete();
			$table->foreign('triggerId')
				->references('id')
				->on('notification_triggers')
				->cascadeOnDelete();
		});
	}

	public function down()
	{
		Schema::dropIfExists('notification_gateway_trigger');
	}
}
