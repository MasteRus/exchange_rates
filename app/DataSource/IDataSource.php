<?php


namespace App\DataSource;


interface IDataSource
{

    /**
     * @return array
     */
    public function getExchangeRates(): array;
}
