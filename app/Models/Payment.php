<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 * @property int    id
 * @property User   user
 * @property string userId
 * @property string reason
 * @property float  amountDfi
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Payment extends Model
{
	use HasFactory;

	public $fillable = [
		'userId',
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
}
