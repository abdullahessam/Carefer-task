<?php

namespace App\Domains\Booking\V1\Interfaces;

use App\Domains\Booking\V1\DTO\OrderData;
use App\Models\Order;
use Illuminate\Support\Collection;

interface IOrder
{
    public function get(): Collection;

    public function store(OrderData $data): Order;

    public function update(int $id, array $data): Order;

    public function delete(int $id): Order;

    public function find(int $id): Order;

}
