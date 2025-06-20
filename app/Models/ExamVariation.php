<?php

namespace App\Models;

use App\Enums\AreaEnum;
use Illuminate\Database\Eloquent\Model;

class ExamVariation extends Model
{
    protected $fillable = [
        'exam_id',
        'variation',
        'position',
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
