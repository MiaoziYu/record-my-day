<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function addRecord(Record $record)
    {
        $this->records()->save($record);
    }

    public function updateRecord()
    {
        $this->records()->find(request('id'))->update([
            'name' => request('name'),
            'started_at' => request('started_at'),
            'score' => request('score'),
            'duration' => request('duration'),
        ]);
    }

    public function deleteRecord($id)
    {
        $this->records()->find($id)->delete();
    }

    public function records() {
        return $this->hasMany(Record::class);
    }
}
