<?php

namespace App\Helpers\Traits;

use App\Models\Line;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\InteractsWithTime;

trait LockService
{
    use InteractsWithTime;

    public function getLockKey()
    {
        return $this->getMorphClass() . '_lock_' . $this->id;

    }
}
