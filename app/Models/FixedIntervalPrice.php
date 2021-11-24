<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property int     id
 * @property string  priceFeedId
 * @property string  priceBase
 * @property float   activePrice
 * @property float   nextPrice
 * @property Carbon  timestamp
 * @property string  validFromTimeString
 * @property boolean isLive
 */
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
		'isLive',
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
