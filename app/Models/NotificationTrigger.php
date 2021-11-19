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
	use Notifiable, HasCooldowns;

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

	public function user(): User
	{
		return $this->gateways()->first()->user;
	}

	public function gateways(): BelongsToMany
	{
		return $this->belongsToMany(NotificationGateway::class, 'notification_gateway_trigger', 'triggerId',
			'gatewayId');
	}

	public function hasGateway(string $type): bool
	{
		return $this->gateways()->where('type', $type)->count() > 0;
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function telegramGateway(): NotificationGateway
	{
		try {
			return $this->gateways()->where('type', NotificationGatewayType::TELEGRAM)->firstOrFail();
		} catch (ModelNotFoundException $e) {
			throw NotificationGatewayException::message(NotificationGatewayType::TELEGRAM, 'not available');
		}
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function mailGateway(): NotificationGateway
	{
		try {
			return $this->gateways()->where('type', NotificationGatewayType::MAIL)->firstOrFail();
		} catch (ModelNotFoundException $e) {
			throw NotificationGatewayException::message(NotificationGatewayType::MAIL, 'not available');
		}
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function webhookGateway(): NotificationGateway
	{
		try {
			return $this->gateways()->where('type', NotificationGatewayType::WEBHOOK)->firstOrFail();
		} catch (ModelNotFoundException $e) {
			throw NotificationGatewayException::message(NotificationGatewayType::WEBHOOK, 'not available');
		}
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function routeNotificationForTelegram(): int
	{
		return $this->telegramGateway()->value;
	}

	/**
	 * @throws \App\Exceptions\NotificationGatewayException
	 */
	public function routeNotificationForMail(): string
	{
		return $this->mailGateway()->value;
	}

	public function preferredLocale(): string
	{
		return $this->user()->language ?? config('app.locale');
	}
}
