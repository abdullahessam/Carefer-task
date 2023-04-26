<?php

namespace App\Domains\Report\V1\Repositories;

use App\Domains\Booking\V1\Enum\OrderStatusEnum;
use App\Domains\Report\V1\Interfaces\IReport;
use App\Domains\Trip\V1\Interfaces\ISeat;
use App\Models\Line;
use App\Models\OrderSeat;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportRepository implements IReport
{
    public function __construct(public User $user)
    {
    }


    public function frequentTripBooked(): Collection
    {
        $report = $this->user->newQuery()->addSelect([
            'most_booked_trip' => function ($query) {
                $query->selectRaw('CONCAT(start_station.name, "-", end_station.name) AS trip_name')
                    ->from('orders')
                    ->join('lines', 'lines.id', '=', 'orders.line_id')
                    ->join('stations AS start_station', 'start_station.id', '=', 'lines.start_station_id')
                    ->join('stations AS end_station', 'end_station.id', '=', 'lines.end_station_id')
                    ->groupBy('orders.id')
                    ->orderByRaw('COUNT(*) DESC')
                    ->limit(1);
            }
        ])->get();
        return $report;
    }
}
