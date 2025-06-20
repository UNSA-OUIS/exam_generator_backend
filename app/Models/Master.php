<?php

namespace App\Models;

use App\Enums\AreaEnum;
use Illuminate\Database\Eloquent\Model;

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
