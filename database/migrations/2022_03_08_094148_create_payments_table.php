<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
	public function up()
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->id();
			$table->string('userId')->index();
			$table->string('reason')->nullable();
			$table->float('amountDfi', 15, 8)->default(0);
			$table->timestamps();

			$table->foreign('userId')
				->references('id')
				->on('users');
		});
	}

	public function down()
	{
		Schema::dropIfExists('payments');
	}
}
