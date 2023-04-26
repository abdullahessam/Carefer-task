<?php

namespace App\Domains\Booking\V1\Services;

use App\Domains\Booking\V1\DTO\OrderData;
use App\Domains\Booking\V1\Interfaces\IOrder;
use App\Domains\Booking\V1\Repositories\OrderRepository;
use App\Domains\Trip\V1\Repositories\LineRepository;
use App\Exceptions\ReservationBusyException;
use App\Jobs\OrderExpireJob;
use App\Models\Line;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class OrderService
{
    private Line $line;
    private LockService $lockService;
    private PriceService $priceService;
    private DiscountService $discountService;


    public function __construct(public Order $order, private int $line_id)
    {
        $this->line = (new LineRepository(new Line()))->find($line_id);
        $this->lockService = new LockService();
        $this->priceService = new PriceService();
        $this->discountService = new DiscountService();
    }

    public function store(OrderData $data): Order
    {
        // Acquire lock for the line
        $lockKey = "line-{$data->line_id}";
        // the lock will be released after 2 minutes as mentioned in the task
        $expiration = 120;
        $lockAcquired = $this->lockService->acquire($lockKey, seconds: $expiration);
        if ($lockAcquired) {
            // Lock acquired, start transaction
            DB::beginTransaction();

            try {
                $countOfSeats = count($data->seat_numbers);
                // Get the price of the line
                $data->sub_total = $this->priceService->getTotalPrice($countOfSeats, $this->line->price);
                // Get the discount of the line
                $data->discount = $this->discountService->getDiscountAmount($countOfSeats, $this->line->price);
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
}
