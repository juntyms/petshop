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

            $res = $client->request('GET', 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');

            $res = $res->getBody()->getContents();

            $encoded = json_encode(simplexml_load_string($res));

            $decoded = json_decode($encoded, true);

        } catch (GuzzleException $e) {
            return response()->json([
                        'error' => $e->getMessage()
                    ], 500);
        }

        return $decoded;

    }
}
