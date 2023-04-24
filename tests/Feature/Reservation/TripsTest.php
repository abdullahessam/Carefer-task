<?php

namespace Tests\Feature\Reservation;

use Tests\TestCase;

class TripsTest extends TestCase
{
    /**
     * return all available trips .
     *
     * @return void
     */
    public function test_index_trips()
    {
        $response = $this->get('/api/v1/trips');

        $response->assertStatus(200);
    }

    /**
     * this function tests show trip details  .
     * @return void
     */
    public function test_show_trips()
    {
        $response = $this->get('/api/v1/trips/1');

        $response->assertStatus(200);
    }
}
