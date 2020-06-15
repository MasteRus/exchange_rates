<?php

namespace App\DataSource;

use Illuminate\Support\Facades\Cache;

// Это основной источник данных по заданию
class CbrDataSource implements IDataSource
{
    protected $url = '';

    /**
     * CbrDataSource constructor.
     */
    public function __construct()
    {
        $this->url = 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL';
    }

    /**
     * @return array
     */
    public function getExchangeRates(): array
    {
        $exchangeRates = [];
        try {
            // создаем клиент для подключения к SOAP-сервису
            $cbr = new \SoapClient($this->url, ['soap_version' => SOAP_1_2, 'exceptions' => true]);
            // Получаем последнюю дату
            $date = $cbr->GetLatestDateTime();
            // Получаем курсы
            $result = $cbr->GetCursOnDateXML([
                'On_date' => $date->GetLatestDateTimeResult
            ]);
            //Поскольку у нас основная валюта рубль - все приводим к ней.
            //В Тестовую задачу не входит определение лучшего пути конвертации
            if ($result->GetCursOnDateXMLResult->any) {
                $xml = new \SimpleXMLElement($result->GetCursOnDateXMLResult->any);
                foreach ($xml->ValuteCursOnDate as $currency) {
                    $exchangeRates[(string)$currency->VchCode] = (float)$currency->Vcurs / (float)$currency->Vnom;
                }
            }
            //Не забываем базовую валюту тоже добавить, её в запросе нет
            $exchangeRates['RUB'] = 1;
            //Кэшируем, чтобы не мучать внешний сервис множеством обращений
            Cache::set('exchange_rates', $exchangeRates, config('currencies.cache_timeout'));
        } catch (\Exception $e) { // на всякий случай обработчик ошибок
            echo 'Error: ' . $e->getMessage();
        }
        return $exchangeRates;
    }
}
