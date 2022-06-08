<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
	public $timestamps = false;
	protected $dateFormat = 'Y-m-d';
	protected $fillable = [
		'date',
		'user_count',
		'vault_count',
		'sum_telegram_messages',
		'sum_mail_messages',
		'sum_webhook_messages',
		'sum_trigger_notifications',
		'sum_phone_messages',
		'sum_daily_messages',
		'sum_may_liquidate_notifications',
		'sum_in_liquidation_notifications',
		'sum_collateral',
		'sum_loan',
		'avg_ratio',
		'median_ratio',
	];
	protected $hidden = [
		'id',
	];
}
