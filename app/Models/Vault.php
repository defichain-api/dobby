<?php

namespace App\Models;

use App\Enum\VaultStates;
use App\Events\VaultUpdatingRatioEvent;
use App\Events\VaultUpdatingStateEvent;
use Envant\Fireable\FireableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @mixin \Eloquent
 * @property string     vaultId
 * @property Collection users
 * @property LoanScheme loanScheme
 * @property string     loanSchemeId
 * @property string     ownerAddress
 * @property string     state
 * @property array      collateralAmounts
 * @property array      loanAmounts
 * @property array      interestAmounts
 * @property float      collateralValue
 * @property float      loanValue
 * @property float      interestValue
 * @property float      informativeRatio
 * @property integer    collateralRatio
 * @property integer    liquidationHeight
 * @property integer    batchCount
 * @property integer    liquidationPenalty
 * @property array      batches
 */
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
		'state'           => [
			VaultStates::INLIQUIDATION => VaultUpdatingStateEvent::class,
			VaultStates::MAYLIQUIDATE  => VaultUpdatingStateEvent::class,
			VaultStates::FROZEN        => VaultUpdatingStateEvent::class,
			VaultStates::ACTIVE        => VaultUpdatingStateEvent::class,
		],
		'collateralRatio' => VaultUpdatingRatioEvent::class,
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
				cache([sprintf('dirty_%s_state', $vault->vaultId) => $dirtyState], now()->addMinutes(5));
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
}
