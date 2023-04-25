<?php

namespace App\Http\Controllers\Api\V1\Trip;

use App\Domains\Trip\V1\Interfaces\ILine;
use App\Domains\Trip\V1\Interfaces\ISeat;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Trip\LineResource;
use Illuminate\Http\Request;

class LineController extends Controller
{
    public function __construct(public ILine $line, public ISeat $seat)
    {
    }

    /**
     * Display a listing of lines.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response_success(LineResource::collection($this->line->get()));
    }


    /**
     * Showing a specific line details with available seats .
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $line = $this->line->find($id);
        $availableSeats = $this->seat->availableSeats($line);
        return response_success([
            'line' => new LineResource($line),
            'available_seats' => $availableSeats,
        ]);
    }

}
