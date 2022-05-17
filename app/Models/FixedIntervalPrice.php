<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FixedIntervalPrice extends Model
{
	protected $primaryKey = 'priceFeedId';
	protected $table = 'fixed_interval_prices';
	protected $casts = [
		'timestamp' => 'timestamp',
	];
	protected $fillable = [
		'priceFeedId',
		'priceBase',
		'activePrice',
		'nextPrice',
		'timestamp',
		'blockHeight',
	];

	public function getValidFromTimeStringAttribute(): string
	{
		return Carbon::parse($this->timestamp)->toDateTimeString();
	}

	public function getPriceFeedIdAttribute(): string
	{
		return $this->attributes['priceFeedId'];
	}
}
