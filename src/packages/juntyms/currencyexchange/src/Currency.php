<?php

namespace Juntyms\CurrencyExchange;

class Currency
{
    public static function configNotPublished()
    {
        return is_null(config('exchange'));
    }
}
