<?php

namespace App\Models;

use App\Models\Concerns\UsesUuidPrimary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin \Eloquent
 * @property string userId
 * @property string language
 * @property string theme
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
}
