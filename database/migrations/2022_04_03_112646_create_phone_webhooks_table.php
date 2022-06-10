<?php

use App\Models\PhoneCall;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneWebhooksTable extends Migration
{
	public function up()
	{
		Schema::create('phone_webhooks', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(PhoneCall::class);
			$table->string('state');
			$table->json('payload')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('phone_webhooks');
	}
}
