<?php

namespace Juntyms\CurrencyExchange;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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

        $client = new Client();

        try {

            $res = $client->request('GET', 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');

            $res = $res->getBody()->getContents();

            $encoded = json_encode(simplexml_load_string($res));

            $decoded = json_decode($encoded, true);

            $collection = collect($decoded)
                ->flatten(4)
                ->where('currency', $currency);

            $key = $collection->search(function ($item, $key) use ($currency) {
                return $item['currency'] == $currency;
            });

            if ($key) {

                $this->currency = $collection[$key]["rate"];
            }

        } catch (GuzzleException $e) {
            return response()->json([
                        'error' => $e->getMessage()
                    ], 500);
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
