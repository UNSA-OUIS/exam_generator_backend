<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfinementBlock extends Model
{
    protected $fillable = [
        'confinement_id',
        'block_id',
        'questions_to_do'
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function confinement()
    {
        return $this->belongsTo(Confinement::class);
    }
}
