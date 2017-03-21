<?php

namespace Tests\Feature;

use App\Record;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecordTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_create_record()
    {
        // Arrange
        $user = factory(User::class)->create();

        Auth::login($user);

        $record = [
            'name' => 'watch movie',
            'finished_at' => Carbon::now()->format('Y-m-d'),
            'score' => 10,
            'duration' => 2,
        ];

        // Act
        $response = $this->json('POST', '/api/records/?api_token=' . $user->api_token, $record);

        // Assert
        $response->assertStatus(201);
    }

    /** @test */
    public function user_can_view_records_of_a_certain_date()
    {
        // Arrange
        $user = factory(User::class)->create();

        Auth::login($user);

        factory(Record::class)->create([
            'user_id' => $user->id,
            'name' => 'watch movie',
            'finished_at' => Carbon::today()->format('Y-m-d'),
        ]);
        factory(Record::class)->create([
            'user_id' => $user->id,
            'name' => 'swimming',
            'finished_at' => Carbon::today()->format('Y-m-d'),
        ]);
        factory(Record::class)->create([
            'user_id' => 2,
            'name' => 'eating',
            'finished_at' => Carbon::today()->format('Y-m-d'),
        ]);
        factory(Record::class)->create([
            'user_id' => $user->id,
            'name' => 'playing game',
            'finished_at' => Carbon::yesterday()->format('Y-m-d'),
        ]);

        // Act
        $response = $this->get('/api/records/?finished_at=' . Carbon::today()->format('Y-m-d') . '&api_token=' . $user->api_token);

        // Assert
        $response->assertSee('watch movie');
        $response->assertSee('swimming');
        $response->assertDontSee('eating');
        $response->assertDontSee('playing game');
    }

    /** @test */
    public function user_can_delete_record()
    {
        // Arrange
        $user = factory(User::class)->create();

        Auth::login($user);

        $record = factory(Record::class)->create([
            'user_id' => $user->id,
            'name' => 'watch movie',
        ]);

        // Act
        $deleteResponse = $this->delete('/api/records/'. $record->id . '?api_token=' . $user->api_token);
        $getResponse = $this->get('/api/records/'. $record->id . '?api_token' . $user->api_token);

        // Assert
        $deleteResponse->assertStatus(204);
        $getResponse->assertDontSee('watch movie');
    }

    /** @test */
    public function user_can_edit_record()
    {
        // Arrange
        $user = factory(User::class)->create();

        Auth::login($user);

        $record = factory(Record::class)->create([
            'user_id' => $user->id,
            'name' => 'watch movie',
            'score' => 10,
            'duration' => 2,
        ]);

        // Act
        $putResponse = $this->put('/api/records/'. $record->id .'?api_token=' . $user->api_token, [
            'name' => 'swimming',
            'finished_at' => $record->finished_at,
            'score' => 20,
            'duration' => 1,
        ]);

        $getResponse = $this->get('/api/records/'. $record->id . '?api_token' . $user->api_token);

        // Assert
        $putResponse->assertStatus(204);

        $getResponse->assertDontSee('watch movie');
        $getResponse->assertSee('swimming');
        $getResponse->assertSee('20');
        $getResponse->assertSee('3');
    }
}