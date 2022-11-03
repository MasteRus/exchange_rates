<?php

return [

    'currencies' => ['RUB', 'USD', 'EUR', 'BDSM'],

    'cache_timeout' => 3600,

    'default_source' => env('EXCHANGE_CURRENCY_DATA_SOURCE', 'Cbr'),

    'datasources' => [
        'cbr' => [
            'url' => env('CBR_DATASOURCE_URL'),
        ],
        'exchange-rates' => [
            'url' => env('EXCHANGE_RATES_DATASOURCE_URL'),
            'apikey' => env('EXCHANGE_RATES_DATASOURCE_APIKEY'),
            'base_currency' => env('EXCHANGE_RATES_DATASOURCE_DEFAULT_CURRENCY', 'RUB')
        ]
    ]

];
