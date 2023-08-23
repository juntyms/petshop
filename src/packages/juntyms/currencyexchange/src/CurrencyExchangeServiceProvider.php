<?php

namespace Juntyms\CurrencyExchange;

use Illuminate\Support\ServiceProvider;

class CurrencyExchangeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/exchange.php' => config_path('exchange.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind('CurrencyExchange', function ($app) {
            return new CurrencyExchange();
        });
    }
}
