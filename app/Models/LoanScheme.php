<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property string  name
 * @property integer minCollaterationRatio
 * @property float   interestRate
 * @property boolean isDefault
 */
class LoanScheme extends Model
{
    protected $fillable = [
        'name',
        'minCollaterationRatio',
        'interestRate',
        'isDefault',
    ];
    protected $hidden = ['id', 'created_at', 'updated_at'];
}
