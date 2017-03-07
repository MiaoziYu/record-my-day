<?php

namespace Tests\Feature;

use App\Record;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecordTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_view_score_within_a_date_range()
    {
        // Arrange
        factory(Record::class)->create([
            'started_at' => '2017-03-07',
            'score' => 10,
        ]);
        factory(Record::class)->create([
            'started_at' => '2017-03-06',
            'score' => 20,
        ]);
        factory(Record::class)->create([
            'started_at' => '2017-03-05',
            'score' => -10,
        ]);
        factory(Record::class)->create([
            'started_at' => '2017-03-06',
            'score' => 30,
        ]);
        factory(Record::class)->create([
            'started_at' => '2017-03-04',
            'score' => 40,
        ]);
        factory(Record::class)->create([
            'started_at' => Carbon::createFromFormat('Y-m-d', '2017-03-07')->subDays(60),
            'score' => 70,
        ]);

        // Act
        $response = $this->get('/api/scores/?end_date=2017-03-06');

        // Assert
        $response->assertSee('"date":"2017-03-06"');
        $response->assertSee('"score":"50"');
        $response->assertSee('"date":"2017-03-04"');
        $response->assertSee('"score":"40"');
        $response->assertDontSee('"date":"2017-01-06"');
        $response->assertDontSee('"score":"70"');
    }
}