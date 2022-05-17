<?php

namespace App\Models;

use App\Enum\PhoneCallState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
		'created_at',
		'updated_at',
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
