<?php

use App\Models\LoanScheme;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaultsTable extends Migration
{
	public function up()
	{
		Schema::create('vaults', function (Blueprint $table) {
			$table->string('vaultId')->unique();
			$table->foreignIdFor(LoanScheme::class, 'loanSchemeId');
			$table->string('ownerAddress')->index();
			$table->string('state')->default('active')->index();
			$table->json('collateralAmounts')->nullable();
			$table->json('loanAmounts')->nullable();
			$table->json('interestAmounts')->nullable();
			$table->float('collateralValue', 22, 8)->nullable();
			$table->float('loanValue', 22, 8)->nullable();
			$table->float('interestValue', 22, 8)->nullable();
			$table->float('informativeRatio', 22, 8)->nullable();
			$table->integer('collateralRatio')->nullable();

			$table->unsignedInteger('liquidationHeight')->nullable()->index();
			$table->unsignedInteger('batchCount')->nullable();
			$table->unsignedInteger('liquidationPenalty')->nullable();
			$table->json('batches')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('vaults');
	}
}