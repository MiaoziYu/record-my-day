<?php

namespace Tests\Feature;

use App\Record;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_register()
    {
        // Arrange
        $userInfo = [
            'name' => 'Miaozi',
            'email' => 'my@opendi.com',
            'password' => 'secret',
        ];

        // Act
        $response = $this->post('/register', $userInfo);

        // Assert
        $response->assertRedirect('/');
    }

    /** @test */
    public function user_can_login()
    {
        // Arrange
        $user = factory(User::class)->create();

        // Act
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        // Assert
        $response->assertRedirect('/');
    }

    /** @test */
    public function user_can_logout()
    {
        // Arrange
        $user = factory(User::class)->create();

        auth()->login($user);

        // Act
        $response = $this->get('/logout');

        // Assert
        $response->assertRedirect('/login');
    }
}