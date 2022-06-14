<?php

namespace App\Models;

use App\Enum\VaultStates;
use App\Events\VaultUpdatingNextRatioEvent;
use App\Events\VaultUpdatingStateEvent;
use App\Notifications\VaultActiveNotification;
use Envant\Fireable\FireableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vault extends Model
{
	use FireableAttributes;

	protected $primaryKey = 'vaultId';
	public $incrementing = false;
	protected $fillable = [
		'vaultId',
		'loanSchemeId',
		'ownerAddress',
		'state',
		'collateralAmounts',
		'loanAmounts',
		'interestAmounts',
		'collateralValue',
		'nextCollateralRatio',
		'loanValue',
		'interestValue',
		'informativeRatio',
		'collateralRatio',
		'liquidationHeight',
		'batchCount',
		'liquidationPenalty',
		'batches',
	];
	protected $casts = [
		'vaultId'           => 'string',
		'collateralAmounts' => 'array',
		'loanAmounts'       => 'array',
		'interestAmounts'   => 'array',
		'batches'           => 'array',
	];
	protected array $fireableAttributes = [
		'state'               => [
			VaultStates::INLIQUIDATION => VaultUpdatingStateEvent::class,
			VaultStates::MAYLIQUIDATE  => VaultUpdatingStateEvent::class,
			VaultStates::FROZEN        => VaultUpdatingStateEvent::class,
			VaultStates::ACTIVE        => VaultUpdatingStateEvent::class,
		],
		'nextCollateralRatio' => VaultUpdatingNextRatioEvent::class,
	];

	public function getVaultIdAttribute(): string
	{
		return $this->attributes['vaultId'];
	}

	public static function boot()
	{
		parent::boot();
		self::updated(function (Vault $vault) {
			if (!$vault->isDirty('state')) {
				return;
			}
			$dirtyState = $vault->original['state'];
			$possibleStates = [VaultStates::FROZEN, VaultStates::MAYLIQUIDATE];
			if ((in_array($dirtyState, $possibleStates) && $vault->state === VaultStates::ACTIVE)) {
				// direct send out notifications
				$vault->users->each(function (User $user) use ($vault, $dirtyState) {
					$user->notify(new VaultActiveNotification($vault, $dirtyState, $user->pivot->name));
				});
			}
		});
	}

	public function loanScheme(): BelongsTo
	{
		return $this->belongsTo(LoanScheme::class, 'loanSchemeId', 'id');
	}

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'user_vault', 'vaultId', 'userId')
			->withPivot('name');
	}

	public function usersWithCurrentRatioNotification(): BelongsToMany
	{
		return $this->users()
			->whereHas('setting', function ($query) {
				$query->where('current_ratio_enabled', true);
			});
	}
}
