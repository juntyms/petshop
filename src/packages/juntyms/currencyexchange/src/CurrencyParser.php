<?php

namespace Juntyms\CurrencyExchange;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CurrencyParser
{
    public static function getXML()
    {
        $client = new Client();

        try {
            if (Currency::configNotPublished()) {
                $uri = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
            } else {
                $uri = config('exchange.uri');
            }

            $res = $client->request('GET', $uri);

            $res = $res->getBody()->getContents();

            $encoded = json_encode(simplexml_load_string($res));

            $decoded = json_decode($encoded, true);

        } catch (GuzzleException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return $decoded;
    }
}
