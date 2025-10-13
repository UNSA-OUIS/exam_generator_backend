<?php

namespace App\Models;

use App\Enums\ExamStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Exam extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'matrix_id',
        'user_id',
        'description',
        'total_variations',
    ];

    protected $casts = [
        'status' => ExamStatusEnum::class,
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
