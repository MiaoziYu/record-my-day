<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'name', 'finished_at', 'score', 'duration',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
