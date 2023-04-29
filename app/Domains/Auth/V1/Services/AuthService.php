<?php

namespace App\Domains\Auth\V1\Services;

use App\Domains\Auth\V1\DTO\LoginData;
use App\Domains\Auth\V1\DTO\RegisterData;
use App\Domains\Auth\V1\Interfaces\IAuth;
use App\Http\Resources\Api\V1\Auth\UserResource;

class AuthService
{

    public function __construct(public IAuth $auth)
    {
    }

    public function login(LoginData $loginData)
    {
        $user = $this->auth->login($loginData);
        return [
            'user' => new UserResource($user),
            'token' => $user->token,
        ];
    }

    public function register(RegisterData $registerData)
    {
        $user = $this->auth->register($registerData);
        return [
            'user' => new UserResource($user),
            'token' => $user->token,
        ];
    }


}
