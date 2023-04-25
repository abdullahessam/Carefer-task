<?php

namespace App\Domains\Trip\V1\Repositories;

use App\Domains\Trip\V1\Interfaces\ILine;
use App\Models\Line;
use Illuminate\Support\Collection;

class LineRepository implements ILine
{
    public function __construct(public Line $line)
    {
    }

    /**
     * get all lines and filter them by start and end stations using query parameters if exists.
     * @return Collection
     */
    public function get(): Collection
    {
        $lines = $this->line->filter()->latest()->get();

        return $lines;
    }

    /**
     * get line if exists by id and if not throw 404 error.
     * @param int $id
     * @return Line
     */
    public function find(int $id): Line
    {
        $line = $this->line->findOrFail($id);

        return $line;
    }
}
