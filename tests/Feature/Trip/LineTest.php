<?php

namespace Tests\Feature\Trip;

use App\Domains\Trip\V1\Repositories\LineRepository;
use App\Models\Line;
use Tests\TestCase;

class LineTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_lines()
    {
        $response = $this->get('/api/V1/trips/lines');
        $lines = (new LineRepository(new Line()))->get();
        $response->assertStatus(200);

    }

    public function test_show_line_with_available_seats()
    {

        $line = Line::first();
        $response = $this->get("/api/V1/trips/lines/{$line->id}");
        $response->assertStatus(200);

    }
}
