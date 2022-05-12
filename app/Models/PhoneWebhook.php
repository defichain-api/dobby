<?php

namespace App\Models;

use App\Enum\PhoneCallState;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property int    phone_call_id
 * @property string state
 * @property string payload
 */
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
