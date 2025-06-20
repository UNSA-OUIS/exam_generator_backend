<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionImage extends Model
{
    protected $fillable = [
        'name',
        'path',
        'question_id'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
