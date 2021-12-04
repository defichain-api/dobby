<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property Carbon  date
 * @property integer user_count
 * @property integer vault_count
 * @property array   messages
 * @property float   sum_collateral
 * @property float   sum_loan
 */
class Statistic extends Model
{
	public $timestamps = false;
	protected $dateFormat = 'Y-m-d';
	protected $fillable = [
		'date',
		'user_count',
		'vault_count',
		'messages',
		'sum_collateral',
		'sum_loan',
	];
	protected $hidden = [
		'id',
	];
}
