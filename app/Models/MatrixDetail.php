<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AreaEnum;
use App\Enums\DifficultyEnum;

class MatrixDetail extends Model
{
    protected $fillable = [
        'matrix_id',
        'area',
        'block_id',
        'difficulty',
        'questions_required',
        'questions_to_do',
    ];

    protected $casts = [
        'area' => AreaEnum::class,
        'difficulty' => DifficultyEnum::class,
    ];

    public function matrix()
    {
        return $this->belongsTo(Matrix::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
