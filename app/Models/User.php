<?php

namespace App\Models;

use App\Exceptions\NotificationTriggerNotAvailableException;
use App\Models\Concerns\UseNotificationConfig;
use App\Models\Concerns\UsesUuidPrimary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\ItemNotFoundException;
use Kurozora\Cooldown\HasCooldowns;

class User extends Model
{
	use HasFactory, UsesUuidPrimary, Notifiable, UseNotificationConfig, HasCooldowns;

	protected $fillable = ['id'];
	protected $with = ['setting'];
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public static function boot()
	{
		parent::boot();

		static::created(function (User $model) {
			UserSetting::create([
				'userId' => $model->id,
			]);
		});
	}

	public function vaults(): BelongsToMany
	{
		return $this->belongsToMany(Vault::class, 'user_vault', 'userId', 'vaultId')
			->withPivot('name');
	}

	public function setting(): HasOne
	{
		return $this->hasOne(UserSetting::class, 'userId', 'id');
	}

	public function enabledBetaFeatures(): HasMany
	{
		return $this->hasMany(EnabledBetaFeature::class, 'userId', 'id');
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

	public function payments(): HasMany
	{
		return $this->hasMany(Payment::class, 'userId', 'id');
	}

	public function deposits(): Collection
	{
		return Deposit::where('senderAddress', $this->setting?->depositFromAddress)->get();
	}

	public function credits(): float
	{
		return $this->deposits()->sum('amountDfi') - $this->payments()->sum('amountDfi');
	}

	public function preferredLocale(): string
	{
		return $this->setting->language ?? config('app.locale');
	}

	public function preferredTimezone(): string
	{
		return $this->setting->timezone ?? config('app.timezone');
	}
}
