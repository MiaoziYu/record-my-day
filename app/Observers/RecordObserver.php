<?php

namespace App\Observers;

use App\Record;
use App\Score;

class RecordObserver
{
    /**
     * Listen to the Record created event.
     *
     * @param Record $record
     * @return void
     */
    public function created(Record $record)
    {
        auth()->user()->addScore($record);
    }

    /**
     * Listen to the Record updated event.
     *
     * @param Record $record
     * @return void
     */
    public function updating(Record $record)
    {
        auth()->user()->updateScore($record);
    }

    /**
     * Listen to the Record deleting event.
     *
     * @param Record $record
     * @return void
     */
    public function deleting(Record $record)
    {
        auth()->user()->deleteScore($record);
    }
}