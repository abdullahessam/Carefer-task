<?php

namespace App\Http\Requests\Api\V1\Booking\Order;

use App\Domains\Booking\V1\Interfaces\IOrder;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(IOrder $orderRepo)
    {
        return $orderRepo->find($this->route('order'))->user_id == auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
