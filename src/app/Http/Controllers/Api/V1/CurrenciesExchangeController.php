<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use CurrencyExchange;
use Illuminate\Http\Request;

class CurrenciesExchangeController extends Controller
{
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
