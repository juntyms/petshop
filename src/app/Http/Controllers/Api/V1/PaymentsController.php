<?php

namespace App\Http\Controllers\Api\V1;

use Stripe;
use App\Models\Payment;
use Juntyms\Stripe\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentsController extends Controller
{
    /**
     * @OA\Post(
     *   tags={"Payment"},
     *   path="/api/v1/payments/create",
     *   @OA\Parameter(
     *         name="type",
     *         description="Payment Type",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             enum={"credit_card"}
     *          )
     *     ),
     *   @OA\Parameter(
     *         name="detail",
     *         description="Json Detail",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="object"
     *          )
     *     ),
     *
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found"),
     *   @OA\Response(response=422, description="Unprocessable Entity"),
     *   @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function store(Request $request)
    {
        // uuid
        // type
        // details

        // Stripe::
        if ($request->type === 'credit_card') {

            $card = Card::make()
                ->cardnumber($request->number)
                ->cvc($request->ccv)
                ->expiry($request->expire_date)
                ->getCard();

            $payment = Stripe::currency('usd')
                ->amount(200)
                ->card($card)
                ->payment();

            $details = [
                'holder_name' => trim($request->holder_name),
                'number' => trim($request->number),
                'cvc' => trim($request->ccv),
                'expiry_date' => trim($request->expire_date)
            ];

            var_dump($payment);

            // if ($payment) {
            //     if ($payment->status == "succeeded") {
            //         //return "payment success";

            //         //Save to Database
            //         Payment::create([
            //             'uuid' => fake()->uuid,
            //             'type' => $request->type,
            //             'details' => $details
            //         ]);

            //         //$this->createStripeResponse();

            //     }
            // }

        }
    }
}