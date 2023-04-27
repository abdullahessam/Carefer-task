<?php

namespace App\Domains\Trip\V1\Interfaces;

use App\Models\Line;
use App\Models\Order;
use Illuminate\Support\Collection;

interface ISeat
{
    /**
     * get available seats for line.
     * @param Line $line
     * @return array
     */
    public function availableSeats(Line $line): array;

    public function updateMany(Order $order, array $data): void;

    public function createMany(Order $order, array $data);

    public function orderSeats(Order $order) :Collection;
}
