<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property User    user
 * @property string  txid
 * @property string  senderAddress
 * @property int     block
 * @property float   amountDfi
 * @property Carbon  received_at
 * @property boolean sentInfoToUser
 */
class Deposit extends Model
{
	use HasFactory;

	public $timestamps = false;
	public $fillable = [
		'txid',
		'senderAddress',
		'block',
		'amountDfi',
		'sentInfoToUser',
	];
	public $hidden = [
		'id',
	];

	public function user(): ?User
	{
		return UserSetting::where('depositFromAddress', $this->senderAddress)->first()?->user;
	}
}
