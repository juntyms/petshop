<?php

namespace Juntyms\Stripe;

class Card
{
    private $cardnumber;
    private $month;
    private $year;
    private $cvc;
    private $card;

    public static function make(): self
    {
        return new self();
    }
    public function __construct()
    {
        // $this->cardnumber('4242424242424242');
        // $this->month(11);
        // $this->year(25);
        // $this->cvc(123);
    }

    public function cardnumber(string $cardnumber)
    {
        $this->cardnumber = $cardnumber;

        return $this;
    }

    public function month(int $month)
    {
        $this->month = $month;

        return $this;
    }

    public function year(int $year)
    {
        $this->year = $year;

        return $this;
    }

    public function cvc(int $cvc)
    {
        $this->cvc = $cvc;

        return $this;
    }

    public function getCard()
    {
        return [
            'type' => 'card',
            'card' => [
                'number' => $this->cardnumber,
                'exp_month' => $this->month,
                'exp_year' => $this->year,
                'cvc' => $this->cvc,
            ],
        ];

    }

}