<?php

namespace App\Domains\Report\V1\Interfaces;

use App\Models\Line;
use Illuminate\Support\Collection;

interface IReport
{
    /**
     * get available seats for line.
     * @param Line $line
     * @return array
     */
    public function frequentTripBooked(): Collection;
}
