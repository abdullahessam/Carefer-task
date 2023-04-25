<?php

namespace App\Domains\Booking\V1\Interfaces;

use App\Domains\Booking\V1\DTO\OrderData;
use App\Models\Order;
use Illuminate\Support\Collection;

interface IOrder
{
    public function index(): Collection;

    public function store(OrderData $data): Order;

    public function update(int $id, OrderData $data): Order;

    public function delete(int $id): bool;

    public function confirm(int $id): Order;

    public function show(int $id): Order;
}
