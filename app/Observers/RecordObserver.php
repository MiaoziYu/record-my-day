<?php

namespace App\Observers;

use App\Record;
use App\Score;

class RecordObserver
{
    /**
     * Listen to the User created event.
     *
     * @param Record $record
     * @return void
     */
    public function created(Record $record)
    {
        auth()->user()->saveScore($record);
    }

    /**
     * Listen to the User updated event.
     *
     * @param Record $record
     * @return void
     */
    public function updating(Record $record)
    {
        auth()->user()->updateScore($record);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Record $record
     * @return void
     */
    public function deleting(Record $record)
    {
        auth()->user()->updateScore($record, true);
    }
}