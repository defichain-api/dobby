<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BroadcastMessage extends Model
{
	public $timestamps = false;
	protected $dates = [
		'startTime',
		'endTime',
	];
	protected $fillable = [
		'headline',
		'message',
		'type',
		'startTime',
		'endTime',
	];
	protected $hidden = [
		'id',
	];
}
