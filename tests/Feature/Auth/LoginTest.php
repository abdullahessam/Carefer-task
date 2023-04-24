<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_valid_login_data()
    {

        $response = $this->post('/api/v1/login', $this->create_user());

        $response->assertStatus(200);
    }  public function test_not_valid_login_data()
    {


        $response = $this->post('/api/v1/login', ['email'=>'test@test.com','password'=>'not_valid_password']);

        $response->assertStatus(200);
    }



}
