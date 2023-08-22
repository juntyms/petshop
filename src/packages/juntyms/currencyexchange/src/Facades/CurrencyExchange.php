<?php

namespace Juntyms\CurrencyExchange\Facades;

use Illuminate\Support\Facades\Facade;

class CurrencyExchange extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CurrencyExchange';
    }
}
