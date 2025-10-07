<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'code',
        'name',
        'level_id',
        'parent_block_id',
        'has_text',
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
