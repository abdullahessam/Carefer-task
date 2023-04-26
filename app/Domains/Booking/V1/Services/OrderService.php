<?php

namespace App\Domains\Booking\V1\Services;

use App\Domains\Booking\V1\DTO\OrderData;
use App\Domains\Booking\V1\Enum\OrderStatusEnum;
use App\Domains\Booking\V1\Interfaces\IOrder;
use App\Domains\Booking\V1\Repositories\OrderRepository;
use App\Domains\Trip\V1\Interfaces\ILine;
use App\Domains\Trip\V1\Repositories\LineRepository;
use App\Exceptions\CancelOrderException;
use App\Exceptions\ConfirmingOrderException;
use App\Exceptions\ReservationBusyException;
use App\Http\Requests\Api\V1\Booking\Order\DeleteRequest;
use App\Jobs\OrderExpireJob;
use App\Models\Line;
use App\Models\Order;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;

class OrderService
{
    private LockService $lockService;
    private PriceService $priceService;
    private DiscountService $discountService;

    private ILine $lineRepo;
    const EXPIRATION_TIME = 120;

    public function __construct(public Order $order)
    {
        $this->lockService = new LockService();
        $this->priceService = new PriceService();
        $this->discountService = new DiscountService();
        $this->lineRepo = new LineRepository(new Line());
    }

    public function store(OrderData $data): Order
    {
        $line = $this->lineRepo->find($data->line_id);
        // Acquire lock for the line
        $lockKey = "line-{$data->line_id}";
        // the lock will be released after 2 minutes as mentioned in the task
        $expiration = self::EXPIRATION_TIME;
        $lockAcquired = $this->lockService->acquire($lockKey, seconds: $expiration);
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
                $order = $this->order->create($data->toArray());

                // Create the seats
                $seats = array_map(fn ($number) => ['seat_number' => $number], $data->seat_numbers);
                $order->seats()->createMany($seats);

                // Commit the transaction
                DB::commit();

                // this job used to expire the order after 2 minutes the expiration time and allow another user to reserve the seats
                dispatch(new OrderExpireJob($order))->delay($expiration);

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
            throw new ReservationBusyException("Failed to acquire lock for line {$data->line_id}");
        }

    }

    public function update(array $data)
    {
        $line = $this->lineRepo->find($data['line_id'] ?? $this->order->line_id);

        if (isset($data['seat_numbers']) && $this->seatNumbersChanged($data['seat_numbers'])) {
            $this->updateSeats($data['seat_numbers'], $line->price);
        }
        $this->recalculate($line->price);
        $this->order->update($data);

        return $this->order->refresh();
    }

    protected function seatNumbersChanged(array $newSeatNumbers): bool
    {
        $oldSeatNumbers = $this->order->seats->pluck('seat_number')->toArray();
        return $newSeatNumbers !== $oldSeatNumbers;
    }

    protected function updateSeats(array $newSeatNumbers, float $price): void
    {
        $this->order->seats()->delete();
        $this->order->seats()->createMany(array_map(fn ($number) => ['seat_number' => $number], $newSeatNumbers));
    }

    public function recalculate($price)
    {
        $countOfSeats = $this->order->seats()->count();
        $this->order->sub_total = $this->priceService->getTotalPrice($countOfSeats, $price);
        $this->order->discount = $this->discountService->getDiscountAmount($countOfSeats, $price);
    }


    protected function releaseLock(): void
    {
        $lockKey = "line-{$this->order->line_id}";
        $this->lockService->release($lockKey);
    }

    /**
     * Delete the order and release the lock by soft deleting the order
     * @return true
     */
    public function delete(DeleteRequest $request)
    {
        if ($this->order->status != OrderStatusEnum::PENDING) {
            throw new CancelOrderException('Cannot delete non pending order');
        }
        $this->order->update(['status' => OrderStatusEnum::CANCELLED]);
        $this->releaseLock();
        return true;
    }

    public function confirm()
    {
        throw_if($this->order->status != OrderStatusEnum::PENDING, new ConfirmingOrderException('Cannot confirm non pending order'));
        $this->order->update(['status' => OrderStatusEnum::CONFIRMED]);
        $this->releaseLock();
        return $this->order->refresh();
    }


}
