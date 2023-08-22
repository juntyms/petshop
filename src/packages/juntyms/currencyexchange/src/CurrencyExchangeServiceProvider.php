<?php

namespace Juntyms\CurrencyExchange;

use Illuminate\Support\ServiceProvider;

class CurrencyExchangeServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->bind('CurrencyExchange', function ($app) {
            return new CurrencyExchange();
        });
    }
}
