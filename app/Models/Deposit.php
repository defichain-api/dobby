<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 * @property User   user
 * @property string userId
 * @property string txid
 * @property string senderAddress
 * @property int    block
 * @property float  amountDfi
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Deposit extends Model
{
	use HasFactory;

	public $fillable = [
		'userId',
		'txid',
		'senderAddress',
		'block',
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
}
