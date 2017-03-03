<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewCreateRecordTest extends TestCase
{
    use DatabaseMigrations;
    
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
}
