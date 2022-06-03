<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnabledBetaFeaturesTable extends Migration
{
	public function up()
	{
		Schema::create('enabled_beta_features', function (Blueprint $table) {
			$table->id();
			$table->string('userId')->index();
			$table->string('feature');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('enabled_beta_features');
	}
}
