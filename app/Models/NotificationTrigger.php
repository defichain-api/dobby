<?php

namespace App\Models;

use App\Enum\NotificationGatewayType;
use App\Exceptions\NotificationGatewayException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @mixin \Eloquent
 * @property int        id
 * @property Vault      vault
 * @property string     vaultId
 * @property Collection gateways
 * @property int        ratio
 * @property string     type
 */
class NotificationTrigger extends Model
{
	use Notifiable;

	public $timestamps = false;
	protected $fillable = [
		'vaultId',
		'ratio',
		'type',
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
		return $this->belongsToMany(NotificationGateway::class, 'notification_gateway_trigger', 'triggerId',
			'gatewayId');
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function telegramGateway()
	{
		try {
			return $this->gateways()->where('type', NotificationGatewayType::TELEGRAM)->firstOrFail();
		} catch (ModelNotFoundException $e) {
			throw NotificationGatewayException::message(NotificationGatewayType::TELEGRAM, 'not available');
		}
	}
}
