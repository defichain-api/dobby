<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
	use HasFactory;

	public $fillable = [
		'userId',
		'phone_call_id',
		'reason',
		'amountDfi',
	];
	public $hidden = [
		'id',
		'updated_at',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'userId', 'id');
	}

	public function phoneCall(): BelongsTo
	{
		return $this->belongsTo(PhoneCall::class, 'phone_call_id', 'id');
	}
}
