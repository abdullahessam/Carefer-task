<?php

namespace App\Domains\Booking\V1\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\InteractsWithTime;

class LockService
{
    use InteractsWithTime;

    public function acquire(string $key, int $seconds): bool
    {
        $seconds = max(1, $seconds);
        $value = $this->currentTime() + $seconds + 1;
        $result = Redis::setnx($key, $value);
        if ($result) {
            Redis::expire($key, $seconds);
        }

        return $result;
    }

    public function release(string $key): bool
    {
        $lua = "if redis.call('get', KEYS[1]) == ARGV[1] then return redis.call('del', KEYS[1]) else return 0 end";

        return Redis::eval($lua, 1, $key, Redis::get($key)) !== 0;
    }

    public function refresh(string $key, int $seconds): bool
    {
        $value = $this->currentTime() + $seconds + 1;

        return Redis::setex($key, $seconds, $value);
    }
}
