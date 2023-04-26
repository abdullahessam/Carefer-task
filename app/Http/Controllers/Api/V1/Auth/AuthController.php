<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Domains\Auth\V1\DTO\LoginData;
use App\Domains\Auth\V1\DTO\RegisterData;
use App\Domains\Auth\V1\Interfaces\IAuth;
use App\Exceptions\InvalidCredentialException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function __construct(public IAuth $auth)
    {
    }

    public function login(LoginRequest $request)
    {
        info('login request');
        info($request->validated());
        $login_data = LoginData::from($request->validated());
        try {
            $response = $this->auth->login($login_data);
        } catch (InvalidCredentialException $exception) {
            return response_error($exception->getMessage(), 401);
        }

        return response_success($response);
    }

    public function register(RegisterRequest $request)
    {
        $register_data = RegisterData::from($request->validated());
        $response = $this->auth->register($register_data);

        return response_success($response);

    }
}
