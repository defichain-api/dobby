<?php

namespace App\Models;

use App\Models\Concerns\UseNotificationConfig;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Kurozora\Cooldown\HasCooldowns;

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
	use Notifiable, HasCooldowns, UseNotificationConfig;

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

	public function user(): ?User
	{
		return $this->gateways()?->first()->user;
	}

	public function gateways(): BelongsToMany
	{
		return $this->belongsToMany(NotificationGateway::class, 'notification_gateway_trigger', 'triggerId',
			'gatewayId');
	}

	public function preferredLocale(): string
	{
		return $this->user()->setting->language ?? config('app.locale');
	}
}
