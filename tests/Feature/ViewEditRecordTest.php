<?php

namespace Tests\Feature;

use App\Record;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewEditRecordTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_edit_record()
    {
        // Arrange
        $record = Record::create([
            'name' => 'watch movie',
            'started_at' => Carbon::now()->format('Y-m-d'),
            'score' => 10,
            'duration' => 2,
        ]);

        // Act
        $putResponse = $this->put('/api/records/', [
            'id' => $record->id,
            'name' => 'swimming',
            'started_at' => Carbon::now()->format('Y-m-d'),
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
