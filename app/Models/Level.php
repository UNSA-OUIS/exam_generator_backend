<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * El nivel de agrupacion de la pregunta
 */
class Level extends Model
{
    protected $fillable = [
        'stage',
        'name',
    ];

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }
}
