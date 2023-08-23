<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use CurrencyExchange;

class CurrenciesExchangeController extends Controller
{
    /**
     * @OA\Get(
     *   tags={"Currency Exchange Rate"},
     *   path="/api/v1/currency/{currency}/{amount}",
     *   @OA\Parameter(
     *         name="currency",
     *         description="Currency to convert",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             enum={"USD","JPY","BGN","CZK","DKK","GBP","HUF","PLN","RON","SEK","CHF","ISK","NOK","TRY","AUD","BRL","CAD","CNY","HKD","IDR","ILS","INR","KRW","MXN","MYR","NZD","PHP","SGD","THB","ZAR"}
     *          )
     *     ),
     *   @OA\Parameter(
     *         name="amount",
     *         description="Amount to convert",
     *         in="path",
     *         required=true
     *     ),
     *
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found"),
     *   @OA\Response(response=422, description="Unprocessable Entity"),
     *   @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function convertCurrency($currency, $amount)
    {
        $conversion = CurrencyExchange::currency($currency)
            ->amount($amount)
            ->getConversion();

        return response()->json([
            'Amount' => $conversion
        ]);
    }
}
