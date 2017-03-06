<?php

namespace Tests\Unit;

use App\Record;
use App\Score;
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
        $recordOne = factory(Record::class)->create([
            'score' => -10,
        ]);
        $recordTwo = factory(Record::class)->create([
            'score' => 20,
        ]);
        $recordThree = factory(Record::class)->create([
            'started_at' => Carbon::yesterday()->format('Y-m-d'),
            'score' => -10,
        ]);
        $recordFour = factory(Record::class)->create([
            'started_at' => Carbon::yesterday()->format('Y-m-d'),
            'score' => 30,
        ]);

        // Act

        // Assert
        $this->assertEquals(10, Score::getScore(Carbon::today()->format('Y-m-d')));
        $this->assertEquals(20, Score::getScore(Carbon::yesterday()->format('Y-m-d')));
    }

    /** @test */
    public function can_update_score_when_a_record_was_edited()
    {
        // Arrange
        $recordOne = factory(Record::class)->create([
            'score' => 10,
        ]);
        $recordTwo = factory(Record::class)->create([
            'score' => 20,
        ]);

        // Act
        Record::find($recordTwo->id)->update([
            'name' => $recordTwo->name,
            'started_at' => $recordTwo->started_at,
            'score' => 30,
            'duration' => $recordTwo->duration,
        ]);

        // Assert
        $this->assertEquals(40, Score::getScore(Carbon::today()->format('Y-m-d')));
    }

    /** @test */
    public function can_update_score_when_a_record_was_deleted()
    {
        // Arrange
        $recordOne = factory(Record::class)->create([
            'score' => 10,
        ]);
        $recordTwo = factory(Record::class)->create([
            'score' => 20,
        ]);

        // Act
        Record::find($recordTwo->id)->delete();

        // Assert
        $this->assertEquals(10, Score::getScore(Carbon::today()->format('Y-m-d')));
    }
}
