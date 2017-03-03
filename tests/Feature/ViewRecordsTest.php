<?php

namespace Tests\Feature;

use App\Record;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewRecordsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function user_can_view_records_of_a_certain_date()
    {
        // Arrange
        $recordOne = Record::create([
            'name' => 'watch movie',
            'started_at' => Carbon::today()->format('Y-m-d'),
            'score' => 10,
            'duration' => 2,
        ]);
        $recordTwo = Record::create([
            'name' => 'swimming',
            'started_at' => Carbon::today()->format('Y-m-d'),
            'score' => 20,
            'duration' => 1,
        ]);
        $recordThree = Record::create([
            'name' => 'playing game',
            'started_at' => Carbon::yesterday()->format('Y-m-d'),
            'score' => 0,
            'duration' => 3,
        ]);

        // Act
        $response = $this->get('/api/records/?started_at=' . $recordOne->started_at);

        // Assert
        $response->assertSee('watch movie');
        $response->assertSee('swimming');
        $response->assertDontSee('playing game');
    }
}