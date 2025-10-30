<?php

namespace App\Models;

use App\Enums\AreaEnum;
use Illuminate\Database\Eloquent\Model;

class ExamLayout extends Model
{
    protected $fillable = [
        'exam_id',
        'area',
        'variation',
        'position',
        'question_id',
        'options'
    ];

    protected $casts = [
        'area' => AreaEnum::class,
        'options' => 'json'
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
