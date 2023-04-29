<?php

namespace App\Http\Controllers\Api\V1\Report;

use App\Domains\Report\V1\Interfaces\IReport;
use App\Domains\Report\V1\Services\ReportService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Report\FrequentTipBookedResource;
use Illuminate\Http\Request;

class MostFrequentTripsReportController extends Controller
{
    public function __construct(public ReportService $reportService)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response_success($this->reportService->frequentTripBooked());
    }
}
