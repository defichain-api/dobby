<?php

namespace App\Models;

use App\Models\Concerns\UsesUuidPrimary;
use App\Models\Scope\IsActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @mixin \Eloquent
 * @property string     userId
 * @property string     language
 * @property string     theme
 * @property Collection vaults
 * @property Collection notificationGateways
 * @property Collection notificationGatewaysWithInactive
 */
class User extends Model
{
	use HasFactory, Notifiable, UsesUuidPrimary;

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

	public function notificationGateways(): HasMany
	{
		return $this->hasMany(NotificationGateway::class, 'userId', 'userId');
	}

	public function notificationGatewaysWithInactive(): HasMany
	{
		return $this->hasMany(NotificationGateway::class, 'userId', 'userId')
			->withoutGlobalScope(IsActive::class);
	}
}
