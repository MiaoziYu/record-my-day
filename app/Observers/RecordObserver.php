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
        Score::saveScore($record);
    }

    /**
     * Listen to the User updated event.
     *
     * @param Record $record
     * @return void
     */
    public function updating(Record $record)
    {
        Score::updateScore($record);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Record $record
     * @return void
     */
    public function deleting(Record $record)
    {
        Score::updateScore($record, true);
    }
}