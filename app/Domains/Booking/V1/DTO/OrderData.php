<?php

namespace App\Domains\Booking\V1\DTO;

use App\Models\Line;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class OrderData extends Data
{
    public function __construct(
        #[Required,
            Exists(Line::class, 'id')]
        public int    $line_id,
        #[Required,
            Min(1)]
        public array  $seat_numbers,
        public int    $user_id,
        public float  $discount = 0,
        public float  $sub_total = 0,
        public string $date = '',
    ) {
    }
}
