<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class Score extends Model
{
    protected $guarded = [];

    public static function saveScore(Record $record)
    {
        $score_of_day = Score::where('date', $record['started_at']);

        if ($score_of_day->exists()) {
            $score_of_day->update([
                'score' => $score_of_day->first()['score'] + $record['score'],
            ]);
        } else {
            Score::create([
                'date' => $record['started_at'],
                'score' => $record['score'],
            ]);
        }
    }

    public static function updateScore(Record $record, $delete = false) {
        $score_of_day = Score::where('date', $record['started_at']);

        if ($delete === false) {
            $score_of_record = Record::find($record->id);
            $score = $score_of_day->first()['score'] - $score_of_record['score'] + $record['score'];
        } else {
            $score = $score_of_day->first()['score'] - $record['score'];
        }

        $score_of_day->update([
            'score' => $score,
        ]);
    }

    public static function getScore($date)
    {
        return Score::where('date', $date)->first()['score'];
    }
}
