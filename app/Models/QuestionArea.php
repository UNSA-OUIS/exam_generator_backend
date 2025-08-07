<?php

namespace App\Models;

use App\Enums\AreaEnum;
use Illuminate\Database\Eloquent\Model;

class QuestionArea extends Model
{
    public $incrementing = false;           // No auto-increment
    protected $primaryKey = null;           // No single-column primary key
    protected $keyType = 'string';          // Just in case (UUIDs as strings)
    public $timestamps = false;              // Keeps created_at and updated_at

    protected $fillable = [
        'question_id',
        'area'
    ];

    protected $casts = [
        'area' => AreaEnum::class,
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
