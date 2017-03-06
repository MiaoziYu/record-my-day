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

    /**  @test */
    public function user_can_view_record_listing()
    {
        // Arrange
        $record = factory(Record::class)->create([
            'name' => 'watch movie',
            'score' => 10,
            'duration' => 2,
        ]);

        // Act
        $response = $this->get('/api/records/'. $record->id);

        // Assert
        $response->assertSee('watch movie');
        $response->assertSee('10');
        $response->assertSee('2');
    }

    /** @test */
    public function user_can_create_record()
    {
        // Arrange
        $record = [
            'name' => 'watch movie',
            'started_at' => Carbon::now()->format('Y-m-d'),
            'score' => 10,
            'duration' => 2,
        ];

        // Act
        $response = $this->json('POST', '/api/records/', $record);

        // Assert
        $response->assertStatus(201);
    }

    /** @test */
    public function user_can_view_records_of_a_certain_date()
    {
        // Arrange
        $recordOne = factory(Record::class)->create([
            'name' => 'watch movie',
            'started_at' => Carbon::today()->format('Y-m-d'),
        ]);
        $recordTwo = factory(Record::class)->create([
            'name' => 'swimming',
            'started_at' => Carbon::today()->format('Y-m-d'),
        ]);
        $recordThree = factory(Record::class)->create([
            'name' => 'playing game',
            'started_at' => Carbon::yesterday()->format('Y-m-d'),
        ]);

        // Act
        $response = $this->get('/api/records/?started_at=' . $recordOne->started_at);

        // Assert
        $response->assertSee('watch movie');
        $response->assertSee('swimming');
        $response->assertDontSee('playing game');
    }

    /** @test */
    public function user_can_delete_record()
    {
        // Arrange
        $record = factory(Record::class)->create([
            'name' => 'watch movie',
        ]);

        // Act
        $deleteResponse = $this->delete('/api/records/', ['id' => $record->id]);
        $getResponse = $this->get('/api/records/'. $record->id);

        // Assert
        $deleteResponse->assertStatus(204);
        $getResponse->assertDontSee('watch movie');
    }

    /** @test */
    public function user_can_edit_record()
    {
        // Arrange
        $record = factory(Record::class)->create([
            'name' => 'watch movie',
            'score' => 10,
            'duration' => 2,
        ]);

        // Act
        $putResponse = $this->put('/api/records/', [
            'id' => $record->id,
            'name' => 'swimming',
            'started_at' => $record->started_at,
            'score' => 20,
            'duration' => 1,
        ]);

        $getResponse = $this->get('/api/records/'. $record->id);

        // Assert
        $putResponse->assertStatus(204);

        $getResponse->assertDontSee('watch movie');
        $getResponse->assertSee('swimming');
        $getResponse->assertSee('20');
        $getResponse->assertSee('3');
    }
}