<?php

namespace App\Domains\Trip\V1\Interfaces;

use App\Models\Line;

interface ISeat
{
    /**
     * get available seats for line
     * @param Line $line
     * @return array
     */
    public function availableSeats(Line $line) : array;
}
