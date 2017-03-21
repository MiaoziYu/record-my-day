<?php

namespace Tests\Feature;

use App\Todo;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TodoTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_create_todo()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        $todo = [
            'user_id' => $user->id,
            'content' => 'go to park on sunday',
            'score' => '20',
            'is_finished' => false,
        ];

        // Act
        $response = $this->post('/api/todos/?api_token=' . $user->api_token, $todo);

        // Assert
        $response->assertStatus(201);
    }

    /** @test */
    public function user_can_get_unfinished_todos()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'go to park on sunday',
            'is_finished' => false,
        ]);

        factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'go to cinema today',
            'is_finished' => false,
        ]);

        factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'eat an apple',
            'is_finished' => true,
            'finished_at' => Carbon::now()->format('Y-m-d'),
        ]);

        // Act
        $response = $this->get('/api/todos/?is_finished=0&api_token=' . $user->api_token);

        // Assert
        $response->assertSee('go to park on sunday');
        $response->assertSee('go to cinema today');
        $response->assertDontSee('eat an apple');
    }

    /** @test */
    public function user_can_get_finished_todos()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'go to park on sunday',
            'is_finished' => false,
        ]);

        factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'go to cinema today',
            'is_finished' => false,
        ]);

        factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'eat an apple',
            'is_finished' => true,
            'finished_at' => Carbon::now()->format('Y-m-d'),
        ]);

        // Act
        $response = $this->get('/api/todos/?is_finished=1&api_token=' . $user->api_token);

        // Assert
        $response->assertSee('eat an apple');
        $response->assertDontSee('go to park on sunday');
        $response->assertDontSee('go to cinema today');
    }

    /** @test */
    public function user_can_update_todo()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        $todo = factory(Todo::class)->create([
            'user_id' => $user->id,
        ]);

        // Act
        $response = $this->put('/api/todos/' . $todo->id . '?api_token=' . $user->api_token, [
            'content' => 'go to cinema',
            'is_finished' => true,
            'finished_at' => Carbon::now()->format('Y-m-d'),
        ]);

        // Assert
        $response->assertStatus(204);
    }

    /** @test */
    public function user_can_delete_todo()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        $todo = factory(Todo::class)->create([
            'user_id' => $user->id,
        ]);

        // Act
        $response = $this->delete('/api/todos/' . $todo->id . '?api_token=' . $user->api_token);

        // Assert
        $response->assertStatus(204);
    }

    /** @test */
    public function user_can_delete_all_finished_todos()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'go to park on sunday',
            'is_finished' => true,
            'finished_at' => Carbon::now()->format('Y-m-d'),
        ]);

        factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'go to cinema today',
            'is_finished' => false,
        ]);

        factory(Todo::class)->create([
            'user_id' => $user->id,
            'content' => 'eat an apple',
            'is_finished' => true,
            'finished_at' => Carbon::now()->format('Y-m-d'),
        ]);

        // Act
        $response = $this->delete('/api/todos/?&api_token=' . $user->api_token);

        // Assert
        $response->assertStatus(204);
    }

}