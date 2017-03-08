<?php

namespace App;

use Carbon\Carbon;
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

    public function records() {
        return $this->hasMany(Record::class);
    }

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function showRecord($id)
    {
        return $this->records()->find($id);
    }

    public function showRecords()
    {
        if (request()->started_at) {
            $records = $this->records()->where('started_at', request()->started_at)->get();
        } else {
            $records = $this->records()->where(Carbon::today()->format('Y-m-d'), request()->started_at)->get();
        }

        return $records;
    }

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

    public function getScore($date)
    {
        return $this->scores()->where('date', $date)->first()['score'];
    }

    public function getScoresWithinDateRange()
    {
        if(request('end_date')) {
            $formattedEndDate = Carbon::createFromFormat('Y-m-d', request('end_date'));
            $scores = $this->scores()->whereBetween('date', [$formattedEndDate->subDays(30)->format('Y-m-d'), request('end_date')])->get();
        } else {
            $scores = $this->scores()->whereBetween('date', [Carbon::today()->subDays(30)->format('Y-m-d'), Carbon::today()->format('Y-m-d')])->get();
        }

        return $scores;
    }

    public function saveScore(Record $record)
    {
        $score_of_day = $this->scores()->where('date', $record['started_at']);

        if ($score_of_day->exists()) {
            $score_of_day->update([
                'score' => $score_of_day->first()['score'] + $record['score'],
            ]);
        } else {
            $this->scores()->create([
                'user_id' => $record['user_id'],
                'date' => $record['started_at'],
                'score' => $record['score'],
            ]);
        }
    }

    public function updateScore(Record $record, $delete = false) {
        $score_of_day = $this->scores()->where('date', $record['started_at']);

        if ($delete === false) {
            $score_of_record = $this->records()->find($record->id);
            $score = $score_of_day->first()['score'] - $score_of_record['score'] + $record['score'];
        } else {
            $score = $score_of_day->first()['score'] - $record['score'];
        }

        $score_of_day->update([
            'score' => $score,
        ]);
    }
}

