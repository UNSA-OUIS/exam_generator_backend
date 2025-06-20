<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'matrix_id',
        'user_id',
        'description',
        'total_variations',
    ];

    public function matrix()
    {
        return $this->belongsTo(Matrix::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
