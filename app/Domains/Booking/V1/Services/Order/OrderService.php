<?php

namespace App\Domains\Booking\V1\Services\Order;

use App\Domains\Booking\V1\DTO\OrderData;
use App\Domains\Booking\V1\Enum\OrderStatusEnum;
use App\Domains\Booking\V1\Interfaces\IOrder;
use App\Domains\Trip\V1\Interfaces\ILine;
use App\Domains\Trip\V1\Interfaces\ISeat;
use App\Exceptions\CancelOrderException;
use App\Exceptions\ConfirmingOrderException;
use App\Exceptions\OrderNotPendingException;
use App\Exceptions\ReservationBusyException;
use App\Jobs\OrderExpireJob;
use App\Models\Order;
use App\Services\LockService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    const EXPIRATION_TIME = 120;

    public function __construct(public IOrder $orderRepo, public ISeat $seatRepo, public Order $order, public ILine $lineRepo, public PriceService $priceService, public DiscountService $discountService, public LockService $lockService)
    {
    }

    /**
     * get orders that associated with current user.
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->orderRepo->get();
    }

    /**
     * get order details by id
     * @param $id
     * @return Order
     */
    public function find($id): Order
    {
        return $this->orderRepo->find($id);
    }

    public function store(OrderData $data): Order
    {
        $line = $this->lineRepo->find($data->line_id);

        // Acquire lock for the line
        // the lock will be released after 2 minutes as mentioned in the task
        $lockAcquired = $this->lockService->acquire($line->getLockKey(), self::EXPIRATION_TIME);
        if ($lockAcquired) {
            // Lock acquired, start transaction
            DB::beginTransaction();

            try {
                $countOfSeats = count($data->seat_numbers);
                // Get the price of the line
                $data->sub_total = $this->priceService->getTotalPrice($countOfSeats, $line->price);
                // Get the discount of the line
                $data->discount = $this->discountService->getDiscountAmount($countOfSeats, $line->price);
                // total price will calculate automatically in the database by using store as

                // Create the order
                $order = $this->orderRepo->store($data);

                // Create the seats
                $seats = array_map(fn ($number) => ['seat_number' => $number], $data->seat_numbers);
                $this->seatRepo->createMany($order, $seats);

                // Commit the transaction
                DB::commit();

                // this job used to expire the order after 2 minutes the expiration time and allow another user to reserve the seats
                dispatch(new OrderExpireJob($order))->delay(self::EXPIRATION_TIME);

                // Return the order and refreshing the order to get new calculated fields
                return $order->refresh();
            } catch (\Exception $e) {
                // Something went wrong, rollback the transaction
                DB::rollBack();

                // Throw the exception
                throw $e;
            }

        } else {
            // Failed to acquire lock, throw an exception
            throw new ReservationBusyException("Please wait for 2 minutes");
        }

    }

    /**
     * update the order line and seats and recalculate the order if needed
     * @param array $data
     * @param $id
     * @return Order
     * @throws \Throwable
     */
    public function update(array $data, $id): Order
    {
        //fire exception if the order not pending to prevent update confirmed order or expired or canceled
        throw_unless($this->order->status === OrderStatusEnum::PENDING, new OrderNotPendingException('You can not update non pending order'));
        $order = $this->orderRepo->find($id);
        $line = $this->lineRepo->find($data['line_id'] ?? $order->line_id);
        $orderSeats = $this->seatRepo->orderSeats($order);
        if (isset($data['seat_numbers']) && $this->seatNumbersChanged($orderSeats, $data['seat_numbers'])) {
            $this->updateSeats($data['seat_numbers'], $line->price);
        }
        $this->recalculate($line->price, $data, $orderSeats);
        $order = $this->orderRepo->update($id, $data);

        return $order->refresh();
    }

    /**
     * Check if the seat numbers changed
     * @param Collection $orderSeats
     * @param array $newSeatNumbers
     * @return bool
     */
    protected function seatNumbersChanged(Collection $orderSeats, array $newSeatNumbers): bool
    {
        return $newSeatNumbers !== $orderSeats->toArray();
    }

    /**
     * Update the seats numbers
     * @param array $newSeatNumbers
     * @param float $price
     * @return void
     */
    protected function updateSeats(array $newSeatNumbers, float $price): void
    {
        $seats = array_map(fn ($number) => ['seat_number' => $number], $newSeatNumbers);
        $this->seatRepo->updateMany($this->order, $seats);
    }

    /**
     * Recalculate the order total price and discount
     * @param float $price
     * @param array $data
     * @param Collection $orderSeats
     * @return void
     */
    public function recalculate(float $price, array &$data, Collection $orderSeats): void
    {
        $countOfSeats = $orderSeats->count();
        $data['sub_total'] = $this->priceService->getTotalPrice($countOfSeats, $price);
        $data['discount'] = $this->discountService->getDiscountAmount($countOfSeats, $price);

    }

    /**
     * Release the lock
     * @return void
     */
    protected function releaseLock(): void
    {
        $this->lockService->release($this->order->line->getLockKey());
    }

    /**
     * Delete the order and release the lock by soft deleting the order
     * @return true
     */
    public function delete($id): bool
    {
        $this->order=$this->orderRepo->find($id);
        if ($this->order->status != OrderStatusEnum::PENDING) {
            throw new CancelOrderException('Cannot delete non pending order');
        }
        $this->orderRepo->delete($this->order->id);
        $this->releaseLock();
        return true;
    }

    /**
     * Confirm the order and release the lock
     * @return Order
     * @throws \Throwable
     */
    public function confirm($id): Order
    {
        $this->order = $this->orderRepo->find($id);
        throw_if($this->order->status != OrderStatusEnum::PENDING, new ConfirmingOrderException('Cannot confirm non pending order'));
        $this->orderRepo->update($this->order->id, ['status' => OrderStatusEnum::CONFIRMED]);
        $this->releaseLock();
        return $this->order->refresh();
    }

    /**
     * Expire the order
     * @param int $id
     * @return void
     */
    public function expire(int $id): void
    {
        $order=$this->orderRepo->find($id);
        throw_unless($order->status === OrderStatusEnum::PENDING, new OrderNotPendingException('You can not expire non pending order'));
        $this->orderRepo->update($id, ['status' => OrderStatusEnum::EXPIRED]);
    }


}
