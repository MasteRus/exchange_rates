<?php

namespace App\Providers;

use App\DataSource\IDataSource;
use App\DataSource\DataSource;
use Illuminate\Support\ServiceProvider;

class ConverterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IDataSource::class, function () {
            $class = 'App\\DataSource\\' . config('currencies.default_source') . 'DataSource';
            return new $class();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
