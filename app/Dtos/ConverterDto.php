<?php

namespace App\Dtos;

use Spatie\LaravelData\Data;

class ConverterDto extends Data
{
    public string $currency;
    public float $sum;
}
