<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanSchemesTable extends Migration
{
	public function up()
	{
		Schema::create('loan_schemes', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->string('name')->unique();
            $table->unsignedInteger('minCollaterationRatio');
            $table->unsignedFloat('interestRate', 10, 8);
            $table->boolean('isDefault')->default(false);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('loan_schemes');
	}
}
