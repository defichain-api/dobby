<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBroadcastMessagesTable extends Migration
{
	public function up()
	{
		Schema::create('broadcast_messages', function (Blueprint $table) {
			$table->id();
			$table->string('headline');
			$table->text('message');
			$table->string('type');
			$table->dateTime('startTime');
			$table->dateTime('endTime');
		});
	}

	public function down()
	{
		Schema::dropIfExists('broadcast_messages');
	}
}
