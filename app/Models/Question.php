<?php

namespace App\Models;

use App\Enums\DifficultyEnum;
use App\Enums\QuestionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'statement',
        'difficulty',
        'status',
        'block_id',
        'text_id',
        'formulator_id',
        'validator_id',
        'style_editor_id',
        'digitizer_id',
        'resolution_path',
        'answer',
        'exam_id',
        'confinement_id',
        'created_by',
        'modified_by',
    ];

    protected $hidden = [
        'answer',
    ];

    protected $casts = [
        'difficulty' => DifficultyEnum::class,
        'status' => QuestionStatusEnum::class,
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function text()
    {
        return $this->belongsTo(Text::class);
    }

    public function formulator()
    {
        return $this->belongsTo(Collaborator::class);
    }

    public function validator()
    {
        return $this->belongsTo(Collaborator::class);
    }

    public function style_editor()
    {
        return $this->belongsTo(Collaborator::class);
    }

    public function digitizer()
    {
        return $this->belongsTo(Collaborator::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function confinement()
    {
        return $this->belongsTo(Confinement::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function images()
    {
        return $this->hasMany(QuestionImage::class, 'question_id');
    }

    public function areas()
    {
        return $this->hasMany(QuestionArea::class, 'question_id');
    }
}
