<?php

namespace App\Observers;

use App\Todo;

class TodoObserver
{
    /**
     * Listen to the Todo created event.
     *
     * @param Todo $todo
     * @return void
     */
    public function created(Todo $todo)
    {

    }

    /**
     * Listen to the Todo updated event.
     *
     * @param Todo $todo
     * @return void
     */
    public function updated(Todo $todo)
    {
        if (auth()->user()->todos()->find($todo->id)->is_finished !== $todo['is_finished']) {
            if ($todo['is_finished'] === true) {
                auth()->user()->addScore($todo);
            } elseif ($todo['is_finished'] === false) {
                auth()->user()->deleteScore($todo);
            }
        }
    }

    /**
     * Listen to the Todo deleting event.
     *
     * @param Todo $todo
     * @return void
     */
    public function deleting(Todo $todo)
    {

    }
}