<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin \Eloquent
 * @property NotificationTrigger trigger
 * @property User                user
 * @property string              type
 * @property string              value
 */
class NotificationGateway extends Model
{
	public $timestamps = false;

	protected $hidden = [
		'id',
		'userId',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'userId', 'userId');
	}

	public function triggers(): BelongsToMany
	{
		return $this->belongsToMany(NotificationTrigger::class, 'notification_gateway_trigger', 'gatewayId', 'id');
	}
}
