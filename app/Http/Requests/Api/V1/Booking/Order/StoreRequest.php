<?php

namespace App\Http\Requests\Api\V1\Booking\Order;

use App\Rules\checkAvailableSeatsInTheOrderRule;
use App\Rules\checkUniqueSeatNumbersRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'seat_numbers' => ['required', 'array', 'min:1', 'max:20', new checkUniqueSeatNumbersRule(),
                new checkAvailableSeatsInTheOrderRule(line_id: (int) $this->get('line_id'))],
            'seat_numbers.*' => 'between:1,20',
        ];
    }
}
