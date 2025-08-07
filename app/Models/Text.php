<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'content'
    ];
}
