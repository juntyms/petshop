<?php

namespace Juntyms\CurrencyExchange\Tests;

use Juntyms\CurrencyExchange\CurrencyParser;
use Juntyms\CurrencyExchange\Facades\CurrencyExchange;

class CurrencyExchangeTest extends TestCase
{
    /** @test */
    public function parser_can_parse_uri()
    {
        $decoded = CurrencyParser::getXML();

        $this->assertEquals(count($decoded['Cube']['Cube']['Cube'], COUNT_NORMAL), 30);
    }

    /** @test */
    public function can_return_the_conversion_value()
    {
        $value = CurrencyExchange::currency('PHP')
            ->amount(100)
            ->getConversion();


        $this->assertIsFloat($value);

    }
}
