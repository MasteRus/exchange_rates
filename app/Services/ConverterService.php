<?php


namespace App\Services;


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
            $class = 'App\\DataSource\\'.config('currencies.default_source') . 'DataSource';
            $dataSource = new $class();
            $exchangeRates = $dataSource->getExchangeRates();
        }
        return $exchangeRates;
    }

}
