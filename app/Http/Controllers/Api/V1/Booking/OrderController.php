<?php

namespace App\Http\Controllers\Api\V1\Booking;

use App\Domains\Booking\V1\DTO\OrderData;
use App\Domains\Booking\V1\Interfaces\IOrder;
use App\Exceptions\CancelOrderException;
use App\Exceptions\OrderNotPendingException;
use App\Exceptions\ReservationBusyException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Booking\Order\ShowRequest;
use App\Http\Requests\Api\V1\Booking\Order\StoreRequest;
use App\Http\Requests\Api\V1\Booking\Order\UpdateRequest;
use App\Http\Resources\Api\V1\Booking\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(public IOrder $order)
    {
    }

    /**
     * Display a listing of orders that associated with current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response_success([
            OrderResource::collection($this->order->get()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            $order = $this->order->store(OrderData::from(
                $request->validated()
                + ['user_id' => auth()->id(), 'date' => now()->toDateString()]
            ));
        } catch (ReservationBusyException $exception) {
            return response_error(['message' => $exception->getMessage()]);
        }

        return response_success(new OrderResource($order));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, int $id)
    {
        return response_success(new OrderResource($this->order->find($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, int $id)
    {
        try {
            $order = $this->order->update($id, $request->validated());
        } catch (ReservationBusyException|OrderNotPendingException $exception) {
            return response_error(['message' => $exception->getMessage()]);
        }
        return response_success(new OrderResource($order));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->order->delete($id);
            return response_success(['message' => 'Order deleted successfully']);
        } catch (CancelOrderException $exception) {
            return response_error(['message' => $exception->getMessage()]);
        }
    }
}
