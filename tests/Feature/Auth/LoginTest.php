<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
//    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_valid_login_data()
    {

        $created_user = \App\Models\User::factory()->create();
        $response = $this->post(
            '/api/V1/auth/login',
            ['email' => $created_user->email, 'password' => 'password'],
            ['accept' => 'application/json']
        );
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ],
                    'token'
                ],
                'message',
                'status'
            ]);
    }

    public function test_not_valid_login_data()
    {

        $response = $this->post(
            '/api/V1/auth/login',
            ['email' => 'test@test.com', 'password' => 'not_valid_password'],
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',

            ]
        );

        $response->assertStatus(422);
    }
}
