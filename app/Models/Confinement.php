<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Confinement extends Model
{
    protected $fillable = [
        'name',
        'total',
        'start_date',
        'end_date',
    ];
}
