<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
