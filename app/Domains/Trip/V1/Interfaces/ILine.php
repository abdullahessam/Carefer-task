<?php

namespace App\Domains\Trip\V1\Interfaces;

use App\Models\Line;
use Illuminate\Support\Collection;

interface ILine
{
    /**
     * list all lines.
     * @return array
     */
    public function get(): Collection;

    /**
     * get line by id.
     * @param int $id
     * @return Line
     */
    public function find(int $id): Line;
}
