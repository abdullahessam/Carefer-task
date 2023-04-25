<?php

namespace App\Domains\Auth\V1\DTO;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class RegisterData extends Data
{
    public function __construct(
        #[Required,
            StringType,
            Max(255)]
        public string $name,
        #[Required,
            StringType,
            Max(255),
            Email,
            Unique('users', 'email')]
        public string $email,
        #[Required,
            StringType,
            Max(255)]
        public string $password
    ) {
    }
}
