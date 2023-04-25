<?php

namespace App\Services\Auth\V1\Repositories;

use App\Exceptions\InvalidCredentialException;
use App\Http\Resources\Api\V1\Auth\UserResource;
use App\Models\User;
use App\Services\Auth\V1\DTO\LoginData;
use App\Services\Auth\V1\DTO\RegisterData;
use App\Services\Auth\V1\Interfaces\IAuth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements IAuth
{
    public function __construct(public User $user)
    {
    }

    /**
     * login user
     * @param LoginData $loginData
     * @return array
     * @throws \Throwable
     */
    public function login(LoginData $loginData): array
    {
        //check if credentials are valid or throw an exception
        throw_unless(Auth::attempt($loginData->toArray()), new InvalidCredentialException('Invalid email/password'));

        // get user by email

        $user = $this->user->getByEmail($loginData->email);

        $user->token = $user->createToken('carefer-token')->plainTextToken;

        return [
            'user' => new UserResource($user),
            'token' => $user->token
        ];
    }

    /**
     * register user
     * @param RegisterData $registerData
     * @return array
     */
    public function register(RegisterData $registerData): array
    {
        $user = $this->user->create($registerData->toArray());
        $user->token = $user->createToken('carefer-token')->plainTextToken;
        return [
            'user' => new UserResource($user),
            'token' => $user->token
        ];
    }
}
