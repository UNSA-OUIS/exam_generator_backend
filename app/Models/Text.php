<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Textos elaborados para los examenes, pertenecen a varias preguntas
 */
class Text extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'content'
    ];
}
