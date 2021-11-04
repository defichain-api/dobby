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
		});
	}

	public function down()
	{
		Schema::dropIfExists('user_vault');
	}
}
