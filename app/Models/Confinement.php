<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Confinement extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'total',
        'start_date',
        'end_date',
    ];

    public function requirements()
    {
        return $this->hasMany(ConfinementRequirement::class);
    }
}
