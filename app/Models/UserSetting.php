<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 * @property User   user
 * @property string userId
 * @property string language
 * @property string theme
 * @property string summary_interval
 */
class UserSetting extends Model
{
	public $timestamps = false;
	protected $fillable = [
		'userId',
		'language',
		'theme',
		'summary_interval',
	];
	protected $hidden = [
		'id',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'userId', 'id');
	}
}
