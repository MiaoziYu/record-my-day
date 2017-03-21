<?php

namespace Tests\Unit;

use App\Record;
use App\Score;
use App\Todo;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_create_score_when_a_record_is_created()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        factory(Record::class)->create([
            'user_id' => $user->id,
            'score' => -10,
        ]);
        factory(Record::class)->create([
            'user_id' => $user->id,
            'score' => 20,
        ]);
        factory(Record::class)->create([
            'user_id' => $user->id,
            'finished_at' => Carbon::yesterday()->format('Y-m-d'),
            'score' => -10,
        ]);
        factory(Record::class)->create([
            'finished_at' => Carbon::yesterday()->format('Y-m-d'),
            'score' => 30,
        ]);

        // Act

        // Assert
        $this->assertEquals(10, auth()->user()->getScore(Carbon::today()->format('Y-m-d')));
        $this->assertEquals(20, auth()->user()->getScore(Carbon::yesterday()->format('Y-m-d')));
    }

    /** @test */
    public function can_update_score_when_a_record_is_edited()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        factory(Record::class)->create([
            'user_id' => $user->id,
            'score' => 10,
        ]);
        $record = factory(Record::class)->create([
            'user_id' => $user->id,
            'score' => 20,
        ]);

        // Act
        $record->update([
            'name' => $record->name,
            'finished_at' => $record->finished_at,
            'score' => 30,
            'duration' => $record->duration,
        ]);

        // Assert
        $this->assertEquals(40, auth()->user()->getScore(Carbon::today()->format('Y-m-d')));
    }

    /** @test */
    public function can_update_score_when_a_record_is_deleted()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        factory(Record::class)->create([
            'user_id' => $user->id,
            'score' => 10,
        ]);
        $record = factory(Record::class)->create([
            'user_id' => $user->id,
            'score' => 20,
        ]);

        // Act
        $record->delete();

        // Assert
        $this->assertEquals(10, auth()->user()->getScore(Carbon::today()->format('Y-m-d')));
    }

    /** @test */
    public function can_update_score_when_a_todo_is_toggled()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        $todo = factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'go to park on sunday',
            'score' => 20,
            'is_finished' => false,
        ]);

        // Act
        $todo->update([
            'is_finished' => true,
            'finished_at' => Carbon::today()->format('Y-m-d'),
        ]);

        // Assert
        $this->assertEquals(20, auth()->user()->getScore(Carbon::today()->format('Y-m-d')));

        // Act
        $todo->update([
            'is_finished' => false,
        ]);

        // Assert
        $this->assertEquals(0, auth()->user()->getScore(Carbon::today()->format('Y-m-d')));
    }
}
