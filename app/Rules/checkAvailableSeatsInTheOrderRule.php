<?php

namespace App\Rules;

use App\Domains\Trip\V1\Repositories\LineRepository;
use App\Domains\Trip\V1\Repositories\SeatRepository;
use App\Models\Line;
use App\Models\OrderSeat;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\ValidationException;

class checkAvailableSeatsInTheOrderRule implements Rule
{
    /**
     * Create a new rule instance accepts the line id and use it for validation.
     *
     * @return void
     */
    private Line $line;
    private LineRepository $line_repository;
    private SeatRepository $seat_repository;

    public function __construct(public int $line_id)
    {
        throw_unless($line_id, ValidationException::withMessages(['line_id'=>'line id is required']));
        $line_repository = new LineRepository(new Line());
        $this->line = $line_repository->line->findOrFail($line_id);
        $this->seat_repository = new SeatRepository(new OrderSeat());
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $availableSeats = $this->seat_repository->availableSeats($this->line);
        $selectedSeats = $value;

        $diff = array_diff($selectedSeats, $availableSeats);

        if (count($diff) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'sorry please select available seats.';
    }
}
