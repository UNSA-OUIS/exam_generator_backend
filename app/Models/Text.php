<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Enums\QuestionStatusEnum;

/**
 * Textos elaborados para los examenes, pertenecen a varias preguntas
 */
class Text extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'content',
        'block_id',
        'status',
        'n_questions',
    ];

    protected $casts = [
        'status' => QuestionStatusEnum::class,
    ];
}
