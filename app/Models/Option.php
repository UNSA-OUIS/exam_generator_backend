<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $incrementing = false;           // No auto-increment
    protected $primaryKey = null;           // No single-column primary key
    protected $keyType = 'string';          // Just in case (UUIDs as strings)
    public $timestamps = false;              // Keeps created_at and updated_at

    protected $fillable = [
        'number',
        'question_id',
        'description',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
