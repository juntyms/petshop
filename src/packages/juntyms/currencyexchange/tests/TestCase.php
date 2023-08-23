<?php

namespace Juntyms\CurrencyExchange\Tests;

use Juntyms\CurrencyExchange\CurrencyExchangeServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CurrencyExchangeServiceProvider::class,
        ];
    }
}
