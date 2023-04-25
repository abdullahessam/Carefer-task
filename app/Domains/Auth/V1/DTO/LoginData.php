<?php

namespace App\Services\Auth\V1\DTO;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class LoginData extends Data
{


    public function __construct(
        #[Required, StringType, Max(255), Email, Exists('users', 'email')]
        public string $email,
        #[Required, StringType, Max(255)]
        public string $password)
    {
    }
}
