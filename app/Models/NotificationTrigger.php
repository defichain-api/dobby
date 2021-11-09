<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @mixin \Eloquent
 * @property Vault      vault
 * @property string     userId
 * @property Collection gateways
 * @property int        ratio
 */
class NotificationTrigger extends Model
{
	public $timestamps = false;
	protected $fillable = [
		'userId',
		'vaultId',
		'ratio',
	];
	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
	];

	public function vault(): BelongsTo
	{
		return $this->belongsTo(Vault::class, 'vaultId', 'vaultId');
	}

	public function gateways(): BelongsToMany
	{
		return $this->belongsToMany(NotificationGateway::class, 'notification_gateway_trigger', 'triggerId', 'id');
	}
}
