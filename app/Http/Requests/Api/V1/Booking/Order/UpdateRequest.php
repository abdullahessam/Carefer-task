<?php

namespace App\Http\Requests\Api\V1\Booking\Order;

use App\Domains\Booking\V1\Enum\OrderStatusEnum;
use App\Domains\Booking\V1\Interfaces\IOrder;
use App\Rules\checkAvailableSeatsInTheOrderRule;
use App\Rules\checkUniqueSeatNumbersRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(IOrder $orderRepo)
    {
        $order = $orderRepo->find($this->route('order'));
        $isAuthorized = $order->user_id == auth()->id();
        return ($isAuthorized);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'line_id' => 'required|exists:lines,id',
            'seat_numbers' => ['sometimes', 'array', 'min:1', 'max:20', new checkUniqueSeatNumbersRule(),
                new checkAvailableSeatsInTheOrderRule(line_id: (int)$this->get('line_id'))],
            'seat_numbers.*' => 'between:1,20',
        ];
    }
}
