<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaginationTokensTable extends Migration
{
	public function up()
	{
		Schema::create('paginationTokens', function (Blueprint $table) {
			$table->string('token', 85)->primary();
		});
	}

	public function down()
	{
		Schema::dropIfExists('paginationTokens');
	}
}
