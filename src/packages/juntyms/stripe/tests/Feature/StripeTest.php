<?php

namespace Juntyms\Stripe\Tests\Feature;

use Juntyms\Stripe\Card;
use Juntyms\Stripe\Stripe;
use Juntyms\Stripe\Tests\TestCase;

class StripeTest extends TestCase
{
    /** @test */
    public function can_initialize_card()
    {
        $card = Card::make()->cardnumber('42424')
            ->month(12)
            ->year(27)
            ->cvc(123)
            ->getcard();

        $this->assertArrayHasKey('type', $card);
    }

    /** @test */
    public function can_accept_payment()
    {

        $card = Card::make()->cardnumber('4242424242424242')
                    ->month(12)
                    ->year(27)
                    ->cvc(123)
                    ->getcard();

        $payment = Stripe::make()
            ->card($card)
            ->currency('usd')
            ->amount(200)
            ->payment();

        $this->assertEquals("succeeded", $payment->status);

        //dd($payment->status);
    }

}