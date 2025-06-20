<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'statement',
        'difficulty',
        'status',
        'block_id',
        'text_id',
        'formulator_id',
        'validator_id',
        'style_editor_id',
        'digitador_id',
        'resolution_path',
        'resolution_date',
        'answer',
        'exam_id',
        'confinement_id',
        'created_by',
        'modified_by',
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
        return $this->belongsTo(Participant::class);
    }

    public function validator()
    {
        return $this->belongsTo(Participant::class);
    }

    public function style_editor()
    {
        return $this->belongsTo(Participant::class);
    }

    public function digitador()
    {
        return $this->belongsTo(Participant::class);
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
}
