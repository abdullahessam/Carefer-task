<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_valid_registration()
    {
        $response = $this->post('/api/v1/register', [
                'name' => 'Test User',
                'email' => 'test@test.com',
                'password' => 'password'
            ]);

        $response->assertStatus(200);
    }

    public function test_not_valid_registration()
    {
        $response = $this->post('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test123@test.com',
        ]);

        $response->assertStatus(422);
    }
}
