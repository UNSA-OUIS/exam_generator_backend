<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfinementText extends Model
{
    protected $fillable = [
        'confinement_id',
        'block_id',
        'texts_to_do',
        'questions_per_text'
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
