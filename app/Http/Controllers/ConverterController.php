<?php

namespace App\Http\Controllers;

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
     * @return array
     * @throws NoExchangeRateException
     */
    public function convert(string $inputSum, string $outputCurrency, ConvertRequest $request)
    {
        // Get output currency from query
        $outputCurrency = mb_substr($outputCurrency, 2);
        // Get input count of our money
        $count = (float)($inputSum);

        $currenciesList = config('currencies.currencies');
        $currenciesStr = implode('|', $currenciesList);
        $inputCurrency = [];

        $result = preg_match('/(' . $currenciesStr . ')/', $inputSum, $inputCurrency);
        // Get input currency from query
        if ($result) {
            $inputCurrency = $inputCurrency[0];
        }

        //Get exchange rates
        $result = $this->calculate($inputCurrency, $outputCurrency, $count);
        return $result;
    }

    /**
     * @param string $inputCurrency
     * @param string $outputCurrency
     * @param float $count
     * @throws NoExchangeRateException
     */
    protected function calculate(string $inputCurrency, string $outputCurrency, float $count): array
    {
        $exchangeRates = $this->service->getExchangeRates();

        //Error if we can't get cources info
        if (!(array_key_exists($inputCurrency, $exchangeRates) && array_key_exists($inputCurrency, $exchangeRates))) {
            throw new NoExchangeRateException();
        }

        $result = [
            'currency' => $outputCurrency,
            'sum' => $exchangeRates[$inputCurrency] * $count / $exchangeRates[$outputCurrency],
        ];
        return $result;
    }
}
