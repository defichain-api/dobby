<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNextRatioVaultsTable extends Migration
{
	public function up()
	{
		Schema::table('vaults', function (Blueprint $table) {
			$table->float('nextCollateralRatio', 22, 2)->nullable()->after('collateralRatio');
		});
	}

	public function down()
	{
		Schema::table('vaults', function (Blueprint $table) {
			$table->dropColumn('nextCollateralRatio');
		});
	}
}
