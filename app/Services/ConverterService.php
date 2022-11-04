<?php

namespace App\Services;

use App\DataSource\IDataSource;
use App\Dtos\ConverterDto;
use App\Exceptions\NoExchangeRateException;
use Illuminate\Support\Facades\Cache;

class ConverterService
{
    /**
     * @var IDataSource
     */
    private IDataSource $exchangeSource;

    /**
     * ConverterService constructor.
     * @param IDataSource $exchangeSource
     */
    public function __construct(IDataSource $exchangeSource)
    {
        $this->exchangeSource = $exchangeSource;
    }

    /**
     * @param string $inputCurrency
     * @param string $outputCurrency
     * @param float $count
     * @return ConverterDto
     * @throws NoExchangeRateException
     */
    public function calculate(string $inputCurrency, string $outputCurrency, float $count): ConverterDto
    {
        $exchangeRates = $this->getExchangeRates();

        //Error if we can't get rates info
        if (!(array_key_exists($inputCurrency, $exchangeRates))) {
            throw new NoExchangeRateException();
        }

        return ConverterDto::from(
            [
                'currency' => $outputCurrency,
                'sum'      => $exchangeRates[$inputCurrency] * $count / $exchangeRates[$outputCurrency],
            ]
        );
    }

    /**
     * @return array
     */
    private function getExchangeRates(): array
    {
        $exchangeRates = Cache::get('exchange_rates', []);
        if (!count($exchangeRates)) {
            $exchangeRates = $this->exchangeSource->getExchangeRates();
        }

        return $exchangeRates;
    }

}
