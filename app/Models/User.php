<?php

namespace App\Models;

use App\Exceptions\NotificationTriggerNotAvailableException;
use App\Models\Concerns\UseNotificationConfig;
use App\Models\Concerns\UsesUuidPrimary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\ItemNotFoundException;
use Kurozora\Cooldown\HasCooldowns;

/**
 * @mixin \Eloquent
 * @property string     id
 * @property string     language
 * @property string     theme
 * @property Collection vaults
 * @property Collection notificationGateways
 */
class User extends Model
{
	use HasFactory, UsesUuidPrimary, Notifiable, UseNotificationConfig, HasCooldowns;

	protected $fillable = [
		'id',
		'language',
		'theme',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function vaults(): BelongsToMany
	{
		return $this->belongsToMany(Vault::class, 'user_vault', 'userId', 'vaultId')
			->withPivot('name');
	}

	public function notificationTrigger(): Collection
	{
		$gateways = $this->gateways;
		$notificationTrigger = new Collection();
		$gateways->each(function (NotificationGateway $gateway) use (&$notificationTrigger) {
			$gateway->triggers->each(function (NotificationTrigger $trigger) use (&$notificationTrigger) {
				$notificationTrigger->add($trigger);
			});
		});

		return $notificationTrigger->unique('id')->flatten();
	}

	/**
	 * @throws NotificationTriggerNotAvailableException
	 */
	public function nearestTriggerBelowRatio(Vault $vault, int $currentRatio): NotificationTrigger
	{
		try {
			return $this->notificationTrigger()
				->where('vaultId', $vault->vaultId)
				->sortBy('ratio')
				->where('ratio', '>=', $currentRatio)
				->firstOrFail();
		} catch (ItemNotFoundException) {
			throw new NotificationTriggerNotAvailableException();
		}
	}

	public function gateways(): HasMany
	{
		return $this->hasMany(NotificationGateway::class, 'userId', 'id')
			->with('triggers');
	}

	public function preferredLocale(): string
	{
		return $this->language ?? config('app.locale');
	}
}
