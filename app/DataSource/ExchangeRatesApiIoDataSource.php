<?php


namespace App\DataSource;

use Illuminate\Support\Facades\Cache;

// Это альтернативный источник данных
class ExchangeRatesApiIoDataSource implements IDataSource
{
    protected $url = '';

    /**
     * ExchangeRatesApiIoDataSource constructor.
     */
    public function __construct()
    {
        $this->url = 'https://api.exchangeratesapi.io/latest?symbols=RUB&';
    }

    /**
     * @return array
     */
    public function getExchangeRates(): array
    {
        $exchangeRates = [];
        try {
            $currencyList = config('currencies.currencies');

            foreach ($currencyList as $c) {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $this->url . "&base=" . $c,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ]);

                $response = curl_exec($curl);
                if ($response) {
                    $response = json_decode($response);
                }

                $exchangeRates[$c] = $response->rates->RUB;
                curl_close($curl);
            }
            //Кэшируем, чтобы не мучать внешний сервис множеством обращений
            Cache::set('exchange_rates', $exchangeRates, config('currencies.cache_timeout'));
        } catch (\Exception $e) { // на всякий случай обработчик ошибок
            echo 'Error: ' . $e->getMessage();
        }
        return $exchangeRates;

    }
}
