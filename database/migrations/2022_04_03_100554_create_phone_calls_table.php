<?php

use App\Enum\PhoneCallState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneCallsTable extends Migration
{
	public function up()
	{
		Schema::create('phone_calls', function (Blueprint $table) {
			$table->id();
			$table->string('userId')->index();
			$table->string('vaultId')->index();
			$table->string('recipientNumber');
			$table->float('currentCollateralRatio', 22, 2);
			$table->float('nextCollateralRatio', 22, 2);
			$table->string('state')->default(PhoneCallState::INITIATED->value);
			$table->timestamps();

			$table->foreign('userId')
				->references('id')
				->on('users');

			$table->foreign('vaultId')
				->references('vaultId')
				->on('vaults');
		});
	}

	public function down()
	{
		Schema::dropIfExists('phone_calls');
	}
}
