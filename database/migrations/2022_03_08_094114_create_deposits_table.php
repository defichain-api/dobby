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
			$table->string('userId')->index();
			$table->string('txid')->unique();
			$table->string('senderAddress')->index();
			$table->bigInteger('block');
			$table->float('amountDfi')->default(0);
			$table->timestamps();

			$table->foreign('userId')
				->references('id')
				->on('users');
		});
	}

	public function down()
	{
		Schema::dropIfExists('deposits');
	}
}
