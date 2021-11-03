<?php

namespace Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin \Eloquent
 * @property string id
 * @property string language
 * @property string theme
 */
class User
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'language',
        'theme',
    ];
}
