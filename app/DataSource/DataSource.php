<?php


namespace App\DataSource;


abstract class DataSource
{
    protected $url = '';

    abstract public function getExchangeRates(): array;
}
