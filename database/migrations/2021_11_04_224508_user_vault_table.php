<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserVaultTable extends Migration
{
	public function up()
	{
		Schema::create('user_vault', function (Blueprint $table) {
			$table->string('userId');
			$table->string('vaultId');
			$table->unique(['userId', 'vaultId']);

			$table->foreign('userId')
				->references('userId')
				->on('users')
				->cascadeOnDelete();
			$table->foreign('vaultId')
				->references('vaultId')
				->on('vaults')
				->cascadeOnDelete();
		});
	}

	public function down()
	{
		Schema::dropIfExists('user_vault');
	}
}
