<?php

namespace Tests\Unit;

use App\DataSource\CbrDataSource;
use App\DataSource\ExchangeRatesApiIoDataSource;
use App\DataSource\IDataSource;
use App\Dtos\ConverterDto;
use App\Services\ConverterService;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;

class ConverterServiceTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCbrSuccessMock()
    {
        Config::set('currencies.datasources.cbr.url', 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL');
        $mockDataSource = Mockery::mock(CbrDataSource::class)->makePartial();
        $cources = [
            "USD" => 1,
            "EUR" => 1,
            "RUB" => 1,
        ];
        $mockDataSource->shouldReceive('getExchangeRates')
            ->once()
            ->andReturn(
                $cources
            );


        $inputCurrency = "USD";
        $outputCurrency = "EUR";
        $count = 10;

        $convertedDto = ConverterDto::from(
            [
                'currency' => $outputCurrency,
                'sum'      => (float)$count,
            ]);

        $dataSourceService = app()->instance(IDataSource::class, $mockDataSource);

        $converted = app(ConverterService::class)->calculate($inputCurrency, $outputCurrency, $count);

        $this->assertEquals($convertedDto, $converted);
    }

    public function testExchangeRatesMock()
    {
        Config::set('currencies.datasources.exchange-rates.url', 'https://api.apilayer.com/exchangerates_data/latest');
        Config::set('currencies.datasources.exchange-rates.apikey', 'MySuperKey');
        Config::set('currencies.datasources.exchange-rates.base_currency', 'RUB');
        $mockDataSource = Mockery::mock(ExchangeRatesApiIoDataSource::class)->makePartial();
        $cources = [
            "USD" => 1,
            "EUR" => 1,
            "RUB" => 1,
        ];
        $mockDataSource->shouldReceive('getExchangeRates')
            ->once()
            ->andReturn(
                $cources
            );


        $inputCurrency = "USD";
        $outputCurrency = "EUR";
        $count = 10;

        $convertedDto = ConverterDto::from(
            [
                'currency' => $outputCurrency,
                'sum'      => (float)$count,
            ]);

        $dataSourceService = app()->instance(IDataSource::class, $mockDataSource);

        $converted = app(ConverterService::class)->calculate($inputCurrency, $outputCurrency, $count);

        $this->assertEquals($convertedDto, $converted);
    }
}
