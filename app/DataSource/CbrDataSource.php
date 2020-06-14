<?php

namespace App\DataSource;

use Illuminate\Support\Facades\Cache;

class CbrDataSource extends DataSource
{
    public function __construct()
    {
        $this->url = 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL';
    }

    public function getExchangeRates(): array
    {
        $exchangeRates = [];
        try {
            $euro_to = 0;
            $cbr = new \SoapClient($this->url, ['soap_version' => SOAP_1_2, 'exceptions' => true]); // создаем клиент для подключения к SOAP-сервису
            $date = $cbr->GetLatestDateTime();
            $result = $cbr->GetCursOnDateXML([
                'On_date' => $date->GetLatestDateTimeResult
            ]);
            if ($result->GetCursOnDateXMLResult->any) {
                $xml = new \SimpleXMLElement($result->GetCursOnDateXMLResult->any);
                foreach ($xml->ValuteCursOnDate as $currency) {
                    $exchangeRates[(string)$currency->VchCode] = (float)$currency->Vcurs / (float)$currency->Vnom;
                }
            }
            $exchangeRates['RUB'] = 1;
            Cache::set('exchange_rates', $exchangeRates, config('currencies.cache_timeout'));
        } catch (\Exception $e) { // на всякий случай обработчик ошибок
            echo 'Error: ' . $e->getMessage();
        }
        return $exchangeRates;
    }
}
