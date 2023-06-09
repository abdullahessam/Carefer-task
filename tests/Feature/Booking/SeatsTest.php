<?php

namespace Tests\Feature\Booking;

use App\Domains\Trip\V1\Interfaces\ISeat;
use App\Models\Bus;
use App\Models\Line;
use App\Models\Station;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class SeatsTest extends TestCase
{
    use RefreshDatabase;

    /*
    * @var User
    * @description: this variable is used to store user data
    */
    public $user;

    public Station $start_station;
    public Station $end_station;
    public Bus $bus;
    public Line $line;
    public ISeat $seatRepo;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        Redis::flushall();
        $this->user = User::factory()->create();
        $this->start_station = Station::factory()->create();
        $this->end_station = Station::factory()->create();
        $this->bus = Bus::factory()->create();
        $this->createNewLine();

        $this->seatRepo = $this->app->make(ISeat::class);
    }

    public function createNewLine()
    {
        $this->line = Line::factory()->create([
            'start_station_id' => $this->start_station->id,
            'end_station_id' => $this->end_station->id,
            'bus_id' => $this->bus->id,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_prevent_user_from_booking_booked_seats()
    {
        $this->createNewLine();

        $numbers_of_seats = rand(5, 10);
        $seats_numbers = Arr::random($this->seatRepo->availableSeats($this->line), $numbers_of_seats);
        $first_order = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => $seats_numbers,
        ], [
            'Accept' => 'application/json'
        ]);

        $confirm_order = $this->actingAs($this->user)->post('/api/V1/booking/orders/' . $first_order->decodeResponseJson()['data']['id'] . '/confirm', [], [
            'Accept' => 'application/json'
        ]);

        $second_order_with_same_seats = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => $seats_numbers,
        ], [
            'Accept' => 'application/json'
        ]);
        // validate status code
        $first_order->assertStatus(200);

        $second_order_with_same_seats->assertStatus(422);
    }
}
