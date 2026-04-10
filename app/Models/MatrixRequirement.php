<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AreaEnum;

/**
 * Matriz de requerimientos de la modalidad para el examen (se reutiliza en cada examen de la modalidad)
 */
class MatrixRequirement extends Model
{
    protected $fillable = [
        'matrix_id',
        'area',
        'block_id',
        'n_questions',
        'parent_id',
    ];

    protected $casts = [
        'area' => AreaEnum::class,
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
