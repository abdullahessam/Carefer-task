<?php

namespace App\Domains\Booking\V1\Services;

class PriceService
{
    /**
     * Get the total price for a given number of tickets and ticket price.
     * @param int $numTickets
     * @param float $ticketPrice
     * @return float
     */
    public function getTotalPrice(int $numTickets, float $ticketPrice): float
    {
        return $numTickets * $ticketPrice;
    }
}
