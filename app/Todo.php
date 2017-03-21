<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'user_id', 'score', 'content', 'is_finished', 'finished_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
