<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotificationTriggerForeignKey extends Migration
{
	public function up()
	{
		Schema::table('notification_triggers', function (Blueprint $table) {
			$table->dropForeign(['vaultId']);
			$table->foreign('vaultId')
				->references('vaultId')
				->on('vaults')
				->cascadeOnDelete();
		});
	}
}
