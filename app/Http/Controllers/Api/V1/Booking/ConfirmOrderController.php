<?php

namespace App\Http\Controllers\Api\V1\Booking;

use App\Domains\Booking\V1\Interfaces\IOrder;
use App\Exceptions\ConfirmingOrderException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Booking\Order\ConfirmRequest;
use App\Http\Resources\Api\V1\Booking\OrderResource;
use Illuminate\Http\Request;

class ConfirmOrderController extends Controller
{
    public function __construct(public IOrder $order)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ConfirmRequest $request, $orderID)
    {
        try {
            $order = $this->order->confirm($orderID);

            return response_success(new OrderResource($order));
        } catch (ConfirmingOrderException $exception) {
            return response_error($exception->getMessage());
        }

    }
}
