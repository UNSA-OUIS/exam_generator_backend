<?php

namespace App\Models;

use App\Enums\AreaEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * !!Contiene la estructura de preguntas del examen (permutaciones)
 */
class ExamLayout extends Model
{
    protected $fillable = [
        'exam_id',
        'area',
        'variation',
        'position',
        'question_id',
        'options_shuffled'
    ];

    protected $casts = [
        'area' => AreaEnum::class,
        'options_shuffled' => 'json'
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
