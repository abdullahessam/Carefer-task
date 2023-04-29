<?php

namespace App\Domains\Report\V1\Services;

use App\Domains\Report\V1\Interfaces\IReport;
use App\Http\Resources\Api\V1\Report\FrequentTipBookedResource;

class ReportService
{

    public function __construct(public IReport $report)
    {
    }

    public function frequentTripBooked()
    {
        return FrequentTipBookedResource::collection($this->report->frequentTripBooked());
    }
}
