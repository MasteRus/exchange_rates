<?php


namespace App\Services;


use App\DataSource\IDataSource;
use App\DataSource\DataSource;
use Illuminate\Support\Facades\Cache;

class ConverterService
{
    /**
     * @var IDataSource
     */
    private $exchangeSource;

    /**
     * ConverterService constructor.
     * @param IDataSource $exchangeSource
     */
    public function __construct(IDataSource $exchangeSource)
    {
        $this->exchangeSource = $exchangeSource;
    }

    /**
     * @return array
     */
    public function getExchangeRates()
    {
        $exchangeRates = Cache::get('exchange_rates', []);
        if (!count($exchangeRates)) {
            $exchangeRates = $this->exchangeSource->getExchangeRates();
        }
        return $exchangeRates;
    }

}
