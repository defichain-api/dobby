<?php

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
	public function up()
	{
		Schema::create('user_settings', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('userId')->index();
			$table->string('language')->default('en');
			$table->string('theme')->default('light');

			$table->foreign('userId')
				->references('id')
				->on('users');
		});

		Schema::table('users', function(Blueprint $table){
			$table->dropColumn('language');
			$table->dropColumn('theme');
		});

		// create a user setting for all existing users
		$users = User::all();

		$users->each(function (User $user) {
			UserSetting::create([
				'userId' => $user->id,
			]);
		});
	}

	public function down()
	{
		Schema::dropIfExists('user_settings');
		Schema::table('users', function (Blueprint $table) {
			$table->string('language')->default('en')->after('id');
			$table->string('theme')->default('light')->after('language');
		});
	}
}
