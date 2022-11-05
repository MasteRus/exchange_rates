<?php

namespace App\Services;

use App\Dtos\ConverterDto;

interface IConverterService
{
    public function calculate(string $inputCurrency, string $outputCurrency, float $count): ConverterDto;
}
