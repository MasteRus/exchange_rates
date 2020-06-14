<?php


namespace App\Services;


use Illuminate\Support\Facades\Cache;

class ConverterService
{

    /**
     * @return array
     */
    public function getExchangeRates(){
        $exchangeRates = Cache::get('exchange_rates', []);
        if (!count($exchangeRates)) {

            $wsdl = 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL'; // указываем адрес WSDL-описания SOAP-сервиса, оттуда PHP возьмет информацию о доступных методах и их параметрах
            try {
                $euro_to = 0;
                $cbr = new \SoapClient($wsdl, ['soap_version' => SOAP_1_2, 'exceptions' => true]); // создаем клиент для подключения к SOAP-сервису
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

        }
        return $exchangeRates;
    }

    /**
     * @return array
     */
    public function getExchangeRates2(){
        $exchangeRates = Cache::get('exchange_rates', []);
        if (!count($exchangeRates)) {
            try {
                $url = 'https://api.exchangeratesapi.io/latest?symbols=RUB&';
                $currencyList = config('currencies.currencies');

                foreach ($currencyList as $cl) {
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $url . "&base=" . $cl,
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

                    $exchangeRates[$cl] = $response->rates->RUB;
                    curl_close($curl);
                }

                Cache::set('exchange_rates', $exchangeRates, config('currencies.cache_timeout'));
            } catch (\Exception $e) { // на всякий случай обработчик ошибок
                echo 'Error: ' . $e->getMessage();
            }

        }
        return $exchangeRates;
    }
}
