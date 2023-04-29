<?php

namespace App\Domains\Trip\V1\Services;

use App\Domains\Trip\V1\Interfaces\ILine;
use App\Domains\Trip\V1\Interfaces\ISeat;
use App\Http\Resources\Api\V1\Trip\LineResource;

class TripService
{

    public function __construct(public ILine $line,public ISeat $seat)
    {
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function get()
    {

        return LineResource::collection($this->line->get());

    }

    /**
     * get trip line details and available seats based on reserved seats
     * @param $id
     * @return array
     */
    public function find($id)
    {
        $line = $this->line->find($id);
        return [
            'line' => new LineResource($this->line->find($id)),
            'available_seats' => $this->seat->availableSeats($line),
        ];
    }
}
