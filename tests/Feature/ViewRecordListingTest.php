<?php

namespace Tests\Feature;

use App\Record;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewRecordListingTest extends TestCase
{

    use DatabaseMigrations;

    /**  @test */
    public function user_can_view_record_listing()
    {
        $record = Record::create([
            'name' => 'watch movie',
            'started_at' => Carbon::now()->format('Y-m-d'),
            'score' => 10,
            'duration' => 2,
        ]);

        $response = $this->get('/api/records/'. $record->id);

        $response->assertSee('watch movie');
        $response->assertSee('10');
        $response->assertSee('2');
    }
}
