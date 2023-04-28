<?php

namespace Tests\Feature\Trip;

use App\Domains\Trip\V1\Repositories\LineRepository;
use App\Domains\Trip\V1\Repositories\SeatRepository;
use App\Http\Resources\Api\V1\Trip\LineResource;
use App\Models\Line;
use App\Models\Order;
use App\Models\OrderSeat;
use Tests\TestCase;

class LineTest extends TestCase
{
    private $lineRepo;
    private $seatRepo;


    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->lineRepo = new LineRepository(new Line());
        $this->seatRepo = new SeatRepository(new OrderSeat());
    }

    public function test_list_lines()
    {
        $response = $this->get('/api/V1/trips/lines');
        $response->assertStatus(200);
        $resource = LineResource::collection($this->lineRepo->get());
        $this->assertSame(json_decode($resource->response()->getContent(), true)['data'], $response->decodeResponseJson()['data']);
    }

    public function test_line_with_available_seats()
    {

        $line = Line::first();
        $response = $this->get("/api/V1/trips/lines/{$line->id}");
        $response->assertStatus(200);
        $this->assertSame($this->seatRepo->availableSeats($line), $response->decodeResponseJson()['data']['available_seats']);


    }
}
