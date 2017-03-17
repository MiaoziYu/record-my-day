<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class Score extends Model
{
    protected $fillable = [
        'date', 'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
