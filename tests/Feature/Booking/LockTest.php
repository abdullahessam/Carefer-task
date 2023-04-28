<?php

namespace Booking;

use App\Domains\Booking\V1\Enum\OrderStatusEnum;
use App\Domains\Trip\V1\Interfaces\ISeat;
use App\Models\Bus;
use App\Models\Line;
use App\Models\Station;
use App\Models\User;
use Illuminate\Support\Arr;
use Tests\TestCase;

class LockTest extends TestCase
{

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
     * testing if 2 users booked within 2 minutes should prevent the second user from booking until 2 minutes or the first user complete his booking
     * @return void
     * @throws \Throwable
     */
    public function test_line_locked_for_2_minutes()
    {
        $this->createNewLine();

        $first_response = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => Arr::random($this->seatRepo->availableSeats($this->line), 2),
        ], [
            'Accept' => 'application/json'
        ]);
        $second_response = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => Arr::random($this->seatRepo->availableSeats($this->line), 2),
        ], [
            'Accept' => 'application/json'
        ]);

        $second_response_data = $second_response->decodeResponseJson()['data'];
        $second_response->assertStatus(422);
        $this->assertSame($second_response_data['message'], 'Please wait for 2 minutes');
    }

    /**
     * test if the lock is released after 2 minutes
     * @return void
     * @throws \Throwable
     */
    public function test_line_lock_relased_after_2_minutes()
    {
        $this->createNewLine();

        $first_response = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => Arr::random($this->seatRepo->availableSeats($this->line), 2),
        ], [
            'Accept' => 'application/json'
        ]);
        // sleep for 2 minutes until the first order is expired
        sleep(120);

        $get_order_after_expired = $this->actingAs($this->user)->get('/api/V1/booking/orders/' . $first_response->decodeResponseJson()['data']['id'], [
            'Accept' => 'application/json'
        ]);
        $second_response = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => Arr::random($this->seatRepo->availableSeats($this->line), 2),
        ], [
            'Accept' => 'application/json'
        ]);

        $second_response_data = $second_response->decodeResponseJson()['data'];
        $second_response->assertStatus(200);

    }

    /**
     * test if the lock is released after 2 minutes and the first order is confirmed
     * @return void
     * @throws \Throwable
     */
    public function test_line_locked_released_after_the_first_order_confirmed()
    {
        $this->createNewLine();

        $first_response = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => Arr::random($this->seatRepo->availableSeats($this->line), 2),
        ], [
            'Accept' => 'application/json'
        ]);
        $confirm_first_order = $this->actingAs($this->user)->post('/api/V1/booking/orders/' . $first_response->decodeResponseJson()['data']['id'] . '/confirm', [], [
            'Accept' => 'application/json'
        ]);
        $get_order_after_confirmed = $this->actingAs($this->user)->get('/api/V1/booking/orders/' . $first_response->decodeResponseJson()['data']['id'], [
            'Accept' => 'application/json'
        ]);
        $second_response = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => Arr::random($this->seatRepo->availableSeats($this->line), 2),
        ], [
            'Accept' => 'application/json'
        ]);
        $get_order_after_confirmed_data = $get_order_after_confirmed->decodeResponseJson()['data'];
        $second_response->assertStatus(200);
        $this->assertSame(OrderStatusEnum::CONFIRMED, $get_order_after_confirmed_data['status']);
    }

    /**
     * test if the lock is released after 2 minutes and the first order is deleted
     * @return void
     * @throws \Throwable
     */
    public function test_line_locked_released_after_the_first_order_deleted()
    {
        $this->createNewLine();

        $first_response = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => Arr::random($this->seatRepo->availableSeats($this->line), 2),
        ], [
            'Accept' => 'application/json'
        ]);
        $confirm_first_order = $this->actingAs($this->user)->delete('/api/V1/booking/orders/' . $first_response->decodeResponseJson()['data']['id'], [], [
            'Accept' => 'application/json'
        ]);
        $get_order_after_confirmed = $this->actingAs($this->user)->get('/api/V1/booking/orders/' . $first_response->decodeResponseJson()['data']['id'], [
            'Accept' => 'application/json'
        ]);
        $second_response = $this->actingAs($this->user)->post('/api/V1/booking/orders', [
            'line_id' => $this->line->id,
            'seat_numbers' => Arr::random($this->seatRepo->availableSeats($this->line), 2),
        ], [
            'Accept' => 'application/json'
        ]);
        $get_order_after_confirmed_data = $get_order_after_confirmed->decodeResponseJson()['data'];
        $second_response->assertStatus(200);
        $this->assertSame(OrderStatusEnum::CANCELLED, $get_order_after_confirmed_data['status']);
    }
}
