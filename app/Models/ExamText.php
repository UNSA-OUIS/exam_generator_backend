<?php

namespace App\Models;

use App\Enums\AreaEnum;
use Illuminate\Database\Eloquent\Model;

class ExamText extends Model
{
    protected $fillable = [
        'exam_id',
        'area',
        'block_id',
        'n_texts',
        'questions_per_text',
    ];

    protected $casts = [
        'area' => AreaEnum::class,
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
