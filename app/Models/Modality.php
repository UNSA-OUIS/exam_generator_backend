<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modalidades o tipos de examen (ordinario, extraordinario)
 */
class Modality extends Model
{
    protected $table = 'modalities';

    protected $fillable = [
        'name'
    ];
}
