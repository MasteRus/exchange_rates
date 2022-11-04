<?php

namespace App\Http\Controllers;

use App\Dtos\ConverterDto;
use App\Exceptions\NoExchangeRateException;
use App\Http\Requests\ConvertRequest;
use App\Services\ConverterService;

class ConverterController extends Controller
{
    private ConverterService $service;

    /**
     * @param ConverterService $service
     */
    public function __construct(ConverterService $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $inputSum
     * @param string $outputCurrency
     * @param ConvertRequest $request
     * @return ConverterDto
     * @throws NoExchangeRateException
     */
    public function convert(string $inputSum, string $outputCurrency, ConvertRequest $request):ConverterDto
    {
        // Get output currency from query
        $outputCurrency = mb_substr($outputCurrency, 2);
        // Get input count of our money
        $count = (float)($inputSum);

        $inputCurrency = $this->extractInputCurrency($inputSum);

        //Get exchange rates
        return $this->service->calculate($inputCurrency, $outputCurrency, $count);
    }

    /**
     * @param string $inputSum
     * @return string
     */
    protected function extractInputCurrency(string $inputSum):string
    {
        $currenciesStr = implode('|', config('currencies.currencies'));
        $input = [];
        preg_match('/(' . $currenciesStr . ')/', $inputSum, $input);
        return $input[0];
    }

}
