<?php

namespace App\Models;

use App\Enums\DifficultyEnum;
use Illuminate\Database\Eloquent\Model;

class ConfinementRequirement extends Model
{
    protected $fillable = [
        'confinement_id',
        'block_id',
        'difficulty',
        'questions_to_do',
        'parent_id',
    ];

    protected $casts = [
        'difficulty' => DifficultyEnum::class,
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
