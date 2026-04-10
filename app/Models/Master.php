<?php

namespace App\Models;

use App\Enums\AreaEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * Master de preguntas seleccionadas para un area del examen(sorteo), para luego ser sorteadas en el layout
 */
class Master extends Model
{
    protected $fillable = [
        'area',
        'exam_id',
        'question_id',
    ];

    protected $casts = [
        'area' => AreaEnum::class,
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
