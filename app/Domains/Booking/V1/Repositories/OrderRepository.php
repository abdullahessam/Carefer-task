<?php

namespace App\Domains\Booking\V1\Repositories;

use App\Domains\Booking\V1\DTO\OrderData;
use App\Domains\Booking\V1\Enum\OrderStatusEnum;
use App\Domains\Booking\V1\Interfaces\IOrder;
use App\Domains\Booking\V1\Services\Order\OrderService;
use App\Domains\Trip\V1\Interfaces\ILine;
use App\Domains\Trip\V1\Repositories\LineRepository;
use App\Exceptions\OrderNotPendingException;
use App\Models\Line;
use App\Models\Order;
use Illuminate\Support\Collection;

class OrderRepository implements IOrder
{
    /**
     * @param Order $order
     */

    public function __construct(public Order $order, public ILine $line)
    {
    }

    /**
     * Get all  associated with current user orders.
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->order->filter()->get();
    }

    /**
     * create a new order but not confirmed with default status pending.
     * as mentioned in the task, the price of the ticket is the same as the price of the line.
     * and there will be discount if user buys more than 5 tickets.
     * but it didn't mention how much the discount will be.
     * so I will assume that the discount will be 10% of the total price.
     * @param OrderData $data
     * @return Order
     */
    public function store(OrderData $data): Order
    {

        $order = $this->order->create($data->toArray());

        return $order;

    }

    /**
     * update order data (line, seats) but not confirmed.
     * @param int $id
     * @param array $data
     * @return Order
     */
    public function update(int $id, array $data): Order
    {
        $order = $this->order->find($id);
        $order->update($data);
        return $order;
    }

    /**
     * update order status to cancel.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): Order
    {
        return $this->update($id, ['status' => OrderStatusEnum::CANCELLED]);
    }

    /**
     * get order by id associated with the auth user.
     * @param int $id
     * @return Order
     */
    public function find(int $id): Order
    {
        return $this->order->findOrFail($id);
    }


}
