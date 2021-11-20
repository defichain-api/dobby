<?php

namespace App\Models;

use App\Exceptions\NotificationTriggerNotAvailableException;
use App\Models\Concerns\UsesUuidPrimary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\ItemNotFoundException;

/**
 * @mixin \Eloquent
 * @property string     userId
 * @property string     language
 * @property string     theme
 * @property Collection vaults
 * @property Collection notificationGateways
 */
class User extends Model
{
	use HasFactory, UsesUuidPrimary;

	protected $primaryKey = 'userId';
	protected $fillable = [
		'userId',
		'language',
		'theme',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function id(): string
	{
		return $this->userId;
	}

	public function vaults(): BelongsToMany
	{
		return $this->belongsToMany(Vault::class, 'user_vault', 'userId', 'vaultId');
	}

	public function notificationTrigger(): Collection
	{
		$gateways = $this->notificationGateways;
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
	public function nearestTriggerBelowRatio(int $ratio): NotificationTrigger
	{
		try {
			return $this->notificationTrigger()
				->sortBy('ratio')
				->where('ratio', '>=', $ratio)
				->firstOrFail();
		} catch (ItemNotFoundException $e) {
			throw new NotificationTriggerNotAvailableException();
		}
	}

	public function notificationGateways(): HasMany
	{
		return $this->hasMany(NotificationGateway::class, 'userId', 'userId')
			->with('triggers');
	}
}
