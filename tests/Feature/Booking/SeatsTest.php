<?php

namespace Tests\Feature\Booking;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeatsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_prevent_user_from_booking_booked_seats()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
