<?php

namespace App\Http\Controllers\Api\V1\Trip;

use App\Domains\Trip\V1\Interfaces\ILine;
use App\Domains\Trip\V1\Interfaces\ISeat;
use App\Domains\Trip\V1\Services\TripService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Trip\LineResource;

class LineController extends Controller
{
    public function __construct(public TripService $tripService)
    {
    }

    /**
     * Display a listing of lines.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response_success($this->tripService->get());
    }

    /**
     * Showing a specific line details with available seats .
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response_success($this->tripService->find($id));
    }
}
