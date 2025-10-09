<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'code',
        'name',
        'has_text',
        'level_id',
        'parent_block_id',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function parentBlock()
    {
        return $this->belongsTo(Block::class, 'parent_block_id');
    }
}
