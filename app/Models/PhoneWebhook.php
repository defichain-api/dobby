<?php

namespace App\Models;

use App\Enum\PhoneCallState;
use Illuminate\Database\Eloquent\Model;

class PhoneWebhook extends Model
{
	public $fillable = [
		'phone_call_id',
		'state',
		'payload',
	];
	protected $casts = [
		'payload' => 'array',
		'state'   => PhoneCallState::class,
	];
}
