<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_valid_registration()
    {
        $response = $this->post('/api/V1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
    }

    public function test_not_valid_registration()
    {
        $response = $this->post('/api/V1/auth/register', [
            'name' => 'Test User',
            'email' => 'test123@test.com',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
    }
}
