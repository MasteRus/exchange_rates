<?php


namespace App\Services;


use App\DataSource\CbrDataSource;
use App\DataSource\ExchangeRatesApiIoDataSource;
use Illuminate\Support\Facades\Cache;

class ConverterService
{

    /**
     * @return array
     */
    public function getExchangeRates()
    {
        $exchangeRates = Cache::get('exchange_rates', []);
        if (!count($exchangeRates)) {
            $dataSource = new CbrDataSource();
            $exchangeRates = $dataSource->getExchangeRates();
        }
        return $exchangeRates;
    }

    /**
     * @return array
     */
    public function getExchangeRates2()
    {
        $exchangeRates = Cache::get('exchange_rates', []);
        if (!count($exchangeRates)) {
            $dataSource = new ExchangeRatesApiIoDataSource();
            $exchangeRates = $dataSource->getExchangeRates();
        }
        return $exchangeRates;
    }
}
