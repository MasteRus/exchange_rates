<?php

namespace App\Providers;

use App\DataSource\IDataSource;
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
        // Мы хотим сделать расширяемый код, чтобы потом с легкостью можно было
        // заменить источник данных и при этом нам не пришлось бы переписывать
        // Поэтому воспользуемся конфигом и там укажем текущий источник данных
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
