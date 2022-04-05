<?php

namespace App\Models;

use App\Enum\PhoneCallState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Eloquent
 * @property string userId
 * @property User   user
 * @property string vaultId
 * @property Vault  vault
 * @property string recipientNumber
 * @property float  currentCollateralRatio
 * @property float  nextCollateralRatio
 * @property string state
 */
class PhoneCall extends Model
{
	use HasFactory;

	public $fillable = [
		'userId',
		'vaultId',
		'recipientNumber',
		'currentCollateralRatio',
		'nextCollateralRatio',
		'state',
	];
	protected $hidden = [
		'id',
	];
	protected $casts = [
		'state' => PhoneCallState::class,
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'userId', 'id');
	}

	public function vault(): BelongsTo
	{
		return $this->belongsTo(Vault::class, 'vaultId', 'vaultId');
	}

	public function webhooks(): HasMany
	{
		return $this->hasMany(PhoneWebhook::class, 'phone_call_id');
	}

	public function setState(PhoneCallState $state): bool
	{
		return $this->update([
			'state' => $state->value,
		]);
	}
}
