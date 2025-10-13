<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AreaEnum;
use App\Enums\DifficultyEnum;

class ExamRequirement extends Model
{
    protected $fillable = [
        'exam_id',
        'area',
        'block_id',
        'difficulty',
        'n_questions',
        'parent_id',
    ];

    protected $casts = [
        'area' => AreaEnum::class,
        'difficulty' => DifficultyEnum::class,
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function parent()
    {
        return $this->belongsTo(ExamRequirement::class, 'parent_id');
    }
}
