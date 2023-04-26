<?php

namespace App\Jobs;

use App\Domains\Booking\V1\Enum\OrderStatusEnum;
use App\Domains\Booking\V1\Repositories\OrderRepository;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderExpireJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Order $order)
    {
        //
    }

    /**
     * Execute the job.
     * this job will expire the order if not confirmed after 2 minutes.
     *
     * @return void
     */
    public function handle()
    {
        $orderRepository = new OrderRepository(new Order());
        $orderRepository->expire($this->order->id);
    }
}
