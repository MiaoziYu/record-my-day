<?php

namespace Tests\Unit;

use App\Record;
use App\Score;
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
    public function can_create_score()
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
            'started_at' => Carbon::yesterday()->format('Y-m-d'),
            'score' => -10,
        ]);
        factory(Record::class)->create([
            'started_at' => Carbon::yesterday()->format('Y-m-d'),
            'score' => 30,
        ]);

        // Act

        // Assert
        $this->assertEquals(10, auth()->user()->getScore(Carbon::today()->format('Y-m-d')));
        $this->assertEquals(20, auth()->user()->getScore(Carbon::yesterday()->format('Y-m-d')));
    }

    /** @test */
    public function can_update_score_when_a_record_was_edited()
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
        Record::find($record->id)->update([
            'name' => $record->name,
            'started_at' => $record->started_at,
            'score' => 30,
            'duration' => $record->duration,
        ]);

        // Assert
        $this->assertEquals(40, auth()->user()->getScore(Carbon::today()->format('Y-m-d')));
    }

    /** @test */
    public function can_update_score_when_a_record_was_deleted()
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
        Record::find($record->id)->delete();

        // Assert
        $this->assertEquals(10, auth()->user()->getScore(Carbon::today()->format('Y-m-d')));
    }
}
