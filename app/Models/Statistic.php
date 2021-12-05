<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property Carbon  date
 * @property integer user_count
 * @property integer vault_count
 * @property integer sum_telegram_messages
 * @property integer sum_mail_messages
 * @property integer sum_webhook_messages
 * @property integer sum_info_notifications
 * @property integer sum_warning_notifications
 * @property integer sum_daily_messages
 * @property float   sum_collateral
 * @property float   sum_loan
 * @property float   avg_ratio
 */
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
		'sum_info_notifications',
		'sum_warning_notifications',
		'sum_daily_messages',
		'sum_collateral',
		'sum_loan',
		'avg_ratio',
	];
	protected $hidden = [
		'id',
	];
}
