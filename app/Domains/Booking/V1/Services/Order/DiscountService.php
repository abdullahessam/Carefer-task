<?php

namespace App\Domains\Booking\V1\Services\Order;

class DiscountService
{
    /**
     * Get the discount amount based on the number of tickets purchased.
     * @param int $numTickets
     * @param float $ticketPrice
     * @return float
     */
    public function getDiscountAmount(int $numTickets, float $ticketPrice): float
    {
        // Assume 10% discount for buying more than 5 tickets as per the task description
        if ($numTickets >= 5) {
            return $ticketPrice * $numTickets * 0.1;
        } else {
            return 0;
        }
    }
}
