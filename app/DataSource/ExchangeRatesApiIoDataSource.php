<?php

namespace App\DataSource;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

// Это альтернативный источник данных
class ExchangeRatesApiIoDataSource implements IDataSource
{
    private string $url = '';
    private string $apikey;
    private $baseCurrency;

    /**
     * ExchangeRatesApiIoDataSource constructor.
     */
    public function __construct()
    {
        $this->url = config('currencies.datasources.exchange-rates.url');
        $this->apikey = config('currencies.datasources.exchange-rates.apikey');
        $this->baseCurrency = config('currencies.datasources.exchange-rates.base_currency');
    }

    /**
     * @return array
     */
    public function getExchangeRates(): array
    {
        $exchangeRates = [];
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $this->url . "&base=" . $this->baseCurrency,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: text/plain",
                    "apikey: " . $this->apikey
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $exchangeRates = $this->parseResponse($response);
            //Кэшируем, чтобы не мучить внешний сервис множеством обращений
            Cache::set('exchange_rates', $exchangeRates, config('currencies.cache_timeout'));
        } catch (Exception $e) { // на всякий случай обработчик ошибок
            Log::error($e->getMessage());
        }
        return $exchangeRates;
    }

    /**
     * @param bool|string $response
     * @return array
     */
    protected function parseResponse($response): array
    {
        if ($response) {
            $response = json_decode($response);
        }
        return (array)$response->rates;
    }
}
