<?php

namespace App\Domains\Trip\V1\Repositories;

use App\Domains\Booking\V1\Enum\OrderStatusEnum;
use App\Domains\Trip\V1\Interfaces\ISeat;
use App\Models\Line;
use App\Models\Order;
use App\Models\OrderSeat;
use Illuminate\Support\Collection;

class SeatRepository implements ISeat
{
    public function __construct(public OrderSeat $orderSeat)
    {
    }

    /**
     * get available seats in trip line by getting all seats in line and subtracting the seats that are reserved.
     * @param Line $line
     * @return array
     */
    public function availableSeats(Line $line): array
    {
        // assume that every bus has 20 seats as described in the task
        $seats = range(1, 20);
        $reservedSeats = $this->orderSeat
            ->whereHas('order', function ($query) use ($line) {
                $query
                    ->where('line_id', $line->id)
                    ->where('date', now()->toDateString())
                    ->where('status', OrderStatusEnum::CONFIRMED);
            })
            ->pluck('seat_number')
            ->toArray();

        return array_diff($seats, $reservedSeats);
    }

    public function updateMany(Order $order, array $data): void
    {
        $order->seats()->delete();
        $this->createMany($order, $data);

    }

    public function createMany(Order $order, array $data)
    {
        $order->seats()->createMany($data);
    }

    public function orderSeats(Order $order): Collection
    {
        return $order->seats;
    }
}
