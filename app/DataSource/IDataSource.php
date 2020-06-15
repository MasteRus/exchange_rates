<?php


namespace App\DataSource;


interface IDataSource
{

    public function getExchangeRates(): array;
}
