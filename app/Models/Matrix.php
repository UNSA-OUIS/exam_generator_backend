<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matrix extends Model
{
    protected $fillable = [
        'year',
        'process_id',
        'total_alternatives',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function details()
    {
        return $this->hasMany(MatrixDetail::class);
    }
}
