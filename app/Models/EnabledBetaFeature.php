<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnabledBetaFeature extends Model
{
	protected $fillable=[
		'userId',
		'feature',
	];
}
