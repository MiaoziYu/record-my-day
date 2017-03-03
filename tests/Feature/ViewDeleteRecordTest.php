<?php

namespace Tests\Feature;

use App\Record;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewDeleteRecordTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_delete_record()
    {
        // Arrange
        $record = Record::create([
            'name' => 'watch movie',
            'started_at' => Carbon::now()->format('Y-m-d'),
            'score' => 10,
            'duration' => 2,
        ]);

        // Act
        $deleteResponse = $this->delete('/api/records/', ['id' => $record->id]);
        $getResponse = $this->get('/api/records/'. $record->id);

        // Assert
        $deleteResponse->assertStatus(204);
        $getResponse->assertDontSee('watch movie');
    }
}
