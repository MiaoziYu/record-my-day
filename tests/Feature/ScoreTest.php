<?php

namespace Tests\Feature;

use App\Record;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_view_score_within_a_date_range()
    {
        // Arrange
        $user = factory(User::class)->create();

        $anotherUser = factory(User::class)->create();

        auth()->login($user);

        factory(Record::class)->create([
            'user_id' => $user->id,
            'started_at' => '2017-03-07',
            'score' => 70,
        ]);
        factory(Record::class)->create([
            'user_id' => $user->id,
            'started_at' => '2017-03-06',
            'score' => 20,
        ]);
        factory(Record::class)->create([
            'user_id' => $user->id,
            'started_at' => '2017-03-06',
            'score' => 30,
        ]);
        factory(Record::class)->create([
            'user_id' => $user->id,
            'started_at' => '2017-03-05',
            'score' => -10,
        ]);
        factory(Record::class)->create([
            'user_id' => $user->id,
            'started_at' => '2017-03-04',
            'score' => 40,
        ]);

        auth()->logout();

        auth()->login($anotherUser);

        // create a record for another user
        factory(Record::class)->create([
            'user_id' => $anotherUser->id,
            'started_at' => '2017-03-04',
            'score' => 10,
        ]);

        // Act

        $response = $this->actingAs($user)->get('/api/scores/?end_date=2017-03-06&api_token=' . $user->api_token);

        // Assert
        $response->assertSee('"date":"2017-03-06"');
        $response->assertSee('"score":"50"');
        $response->assertSee('"date":"2017-03-04"');
        $response->assertSee('"score":"40"');
        // cannot see score out of requested date range
        $response->assertDontSee('"date":"2017-03-07"');
        $response->assertDontSee('"score":"70"');
        // cannot see score of other user
        $response->assertDontSee('"score":"10"');
    }
}