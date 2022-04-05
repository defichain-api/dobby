<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditAttributeToUsersTable extends Migration
{
	public function up()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->float('credit', 15, 8)->default(0)->after('language');
			$table->string('depositFromAddress', 100)->nullable()->after('language');
			$table->string('depositInfoMail', 100)->nullable()->after('depositFromAddress');
		});
	}

	public function down()
	{
		Schema::table('user_settings', function (Blueprint $table) {
			$table->dropColumn('credit');
			$table->dropColumn('depositFromAddress');
		});
	}
}
