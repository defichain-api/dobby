<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVaultNameToUserVault extends Migration
{
	public function up()
	{
		Schema::table('user_vault', function (Blueprint $table) {
			$table->string('name')->nullable();
		});
	}

	public function down()
	{
		Schema::table('user_vault', function (Blueprint $table) {
			$table->dropColumn('name');
		});
	}
}
