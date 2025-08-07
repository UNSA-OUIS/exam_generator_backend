<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionImage extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;              // Keeps created_at and updated_at

    protected $fillable = [
        'id',
        'name',
        'path',
        'question_id'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
