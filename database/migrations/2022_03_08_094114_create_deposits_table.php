<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
	public function up()
	{
		Schema::create('deposits', function (Blueprint $table) {
			$table->id();
			$table->string('txid')->unique();
			$table->string('senderAddress')->index();
			$table->bigInteger('block');
			$table->float('amountDfi', 15, 8)->default(0);
			$table->timestamp('received_at');
			$table->boolean('sentInfoToUser')->default(false);
		});
	}

	public function down()
	{
		Schema::dropIfExists('deposits');
	}
}
