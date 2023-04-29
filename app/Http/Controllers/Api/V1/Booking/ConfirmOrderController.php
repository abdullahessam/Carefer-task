<?php

namespace App\Http\Controllers\Api\V1\Booking;

use App\Domains\Booking\V1\Interfaces\IOrder;
use App\Domains\Booking\V1\Services\Order\OrderService;
use App\Exceptions\ConfirmingOrderException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Booking\Order\ConfirmRequest;
use App\Http\Resources\Api\V1\Booking\OrderResource;
use Illuminate\Http\Request;

class ConfirmOrderController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ConfirmRequest $request, $orderID, OrderService $orderService)
    {

        try {
            $order = $orderService->confirm($orderID);
            return response_success(new OrderResource($order));
        } catch (ConfirmingOrderException $exception) {
            return response_error(['message'=>$exception->getMessage()]);
        } catch (\Throwable $e) {
            return response_error(['message'=>$e->getMessage()]);

        }

    }
}
