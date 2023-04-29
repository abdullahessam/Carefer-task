<?php

namespace App\Domains\Auth\V1\Interfaces;

use App\Domains\Auth\V1\DTO\LoginData;
use App\Domains\Auth\V1\DTO\RegisterData;
use Illuminate\Foundation\Auth\User as Authenticatable;

interface IAuth
{
    /**
     * log user in.
     * @param LoginData $loginData
     * @return array
     */
    public function login(LoginData $loginData): Authenticatable;

    /**
     * register user.
     * @param RegisterData $registerData
     * @return array
     */
    public function register(RegisterData $registerData): Authenticatable;
}
