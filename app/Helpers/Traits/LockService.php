<?php

namespace App\Helpers\Traits;

use App\Models\Line;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\InteractsWithTime;

trait LockService
{
    use InteractsWithTime;

    public function acquire(int $seconds): bool
    {
        $key = $this->getLockKey();
        $seconds = max(1, $seconds);
        $value = $this->currentTime() + $seconds + 1;
        $result = Redis::setnx($key, $value);
        if ($result) {
            Redis::expire($key, $seconds);
        }

        return $result;
    }

    public function release(): bool
    {
        $key = $this->getLockKey();
        $lua = "if redis.call('get', KEYS[1]) == ARGV[1] then return redis.call('del', KEYS[1]) else return 0 end";

        return Redis::eval($lua, 1, $key, Redis::get($key)) !== 0;
    }

    public function refreshLock(int $seconds): bool
    {
        $key = $this->getLockKey();

        $value = $this->currentTime() + $seconds + 1;

        return Redis::setex($key, $seconds, $value);
    }

    public function getLockKey()
    {
        return $this->getMorphClass() . '_lock_' . $this->id;

    }
}
