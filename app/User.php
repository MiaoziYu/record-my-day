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
        'name', 'email', 'password', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Records
    |--------------------------------------------------------------------------
    */

    public function records() {
        return $this->hasMany(Record::class);
    }

    public function showRecord($id)
    {
        return $this->records()->find($id);
    }

    public function showRecords()
    {
        if (request()->finished_at) {
            $records = $this->records()->where('finished_at', request()->finished_at)->get();
        } else {
            $records = $this->records()->where(Carbon::today()->format('Y-m-d'), request()->finished_at)->get();
        }

        return $records;
    }

    public function addRecord(Record $record)
    {
        $this->records()->save($record);
    }

    public function updateRecord($id)
    {
        $collection = collect(request()->all());
        $collection->pull('id');
        $collection->pull('api_token');

        $this->records()->find($id)->update($collection->all());
    }

    public function deleteRecord($id)
    {
        $this->records()->find($id)->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Scores
    |--------------------------------------------------------------------------
    */

    public function scores() {
        return $this->hasMany(Score::class);
    }

    public function getScore($date)
    {
        return $this->scores()->where('finished_at', $date)->first()['value'];
    }

    public function getScoresWithinDateRange()
    {
        if(request('end_date')) {
            $formattedEndDate = Carbon::createFromFormat('Y-m-d', request('end_date'));
            $scores = $this->scores()->whereBetween('finished_at', [$formattedEndDate->subDays(30)->format('Y-m-d'), request('end_date')])->get();
        } else {
            $scores = $this->scores()->whereBetween('finished_at', [Carbon::today()->subDays(30)->format('Y-m-d'), Carbon::today()->format('Y-m-d')])->get();
        }

        return $scores;
    }

    public function addScore($instance)
    {
        $scoreQuery = $this->scores()->where('finished_at', $instance['finished_at']);

        if ($scoreQuery->exists()) {
            $scoreQuery->update([
                'value' => $scoreQuery->first()['value'] + $instance['score'],
            ]);
        } else {
            $this->scores()->create([
                'finished_at' => $instance['finished_at'],
                'value' => $instance['score'],
            ]);
        }
    }

    public function updateScore($instance) {
        $scoreQuery = $this->scores()->where('finished_at', $instance['finished_at']);
        $instanceQuery = $this->records()->find($instance->id);
        $score = $scoreQuery->first()['value'] - $instanceQuery['score'] + $instance['score'];

        $scoreQuery->update([
            'value' => $score,
        ]);
    }

    public function deleteScore($instance)
    {
        $scoreQuery = $this->scores()->where('finished_at', $instance['finished_at']);
        $score = $scoreQuery->first()['value'] - $instance['score'];
        $scoreQuery->update([
            'value' => $score,
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Todos
    |--------------------------------------------------------------------------
    */

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    public function addTodo()
    {
        $this->todos()->create([
            'score' => request('score'),
            'content' => request('content'),
            'is_finished' => request('is_finished'),
        ]);
    }

    public function updateTodo($id)
    {
        $collection = collect(request()->all());
        $collection->pull('id');
        $collection->pull('api_token');

        $this->todos()->find($id)->update($collection->all());
    }

    public function deleteTodo($id)
    {
        $this->todos()->find($id)->delete();
    }

    public function deleteAllFinishedTodos()
    {
        $this->todos()->where('is_finished', true)->delete();
    }

    public function getUnfinishedTodos()
    {
        return $this->todos()->where('is_finished', request('is_finished'))->get();
    }
}

