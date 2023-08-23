<?php

namespace Juntyms\CurrencyExchange;

use Juntyms\CurrencyExchange\CurrencyParser;

class CurrencyExchange
{
    private $rate;
    private $currency;
    private $amount;

    public function __construct()
    {
        $this->rate = 0;
        $this->amount = 0;
        $this->currency = 0;
    }

    public function currency(string $currency)
    {
        $decoded = CurrencyParser::getXML();

        $collection = collect($decoded)
            ->flatten(4)
            ->where('currency', $currency);

        $key = $collection->search(function ($item, $key) use ($currency) {
            return $item['currency'] == $currency;
        });

        if ($key) {

            $this->currency = $collection[$key]["rate"];
        }

        return $this;
    }

    public function amount(float $value)
    {
        $this->amount = $value;

        return $this;
    }

    public function getConversion()
    {
        $this->rate = $this->amount * $this->currency;

        return $this->rate;
    }
}
