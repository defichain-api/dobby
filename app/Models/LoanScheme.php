<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanScheme extends Model
{
    protected $fillable = [
        'name',
        'minCollaterationRatio',
        'interestRate',
    ];
    protected $hidden = ['id', 'created_at', 'updated_at'];
}
