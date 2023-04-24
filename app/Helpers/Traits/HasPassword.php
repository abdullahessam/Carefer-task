<?php

namespace App\Helpers\Traits;

trait HasPassword
{
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }
}
