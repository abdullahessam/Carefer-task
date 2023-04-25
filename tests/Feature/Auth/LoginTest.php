<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_valid_login_data()
    {

        $response = $this->post(
            '/api/V1/auth/login',
            ['email' => 'albertha77@example.net', 'password' => 'password'],
            ['accept' => 'application/json']
        );
        $response->assertStatus(200);
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
