<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedAtIndexVaultsTable extends Migration
{
	public function up()
	{
		Schema::table('vaults', function (Blueprint $table) {
			$table->index(['updated_at']);
		});
	}

	public function down()
	{
		Schema::table('vaults', function (Blueprint $table) {
			$table->dropIndex(['updated_at']);
		});
	}
}
