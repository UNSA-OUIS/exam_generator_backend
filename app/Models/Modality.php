<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modality extends Model
{
    protected $table = 'modalities';

    protected $fillable = [
        'name'
    ];
}
