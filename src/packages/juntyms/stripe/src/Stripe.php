<?php

namespace Juntyms\Stripe;

use Juntyms\Stripe\Card;
use Stripe\StripeClient;
use Illuminate\Support\Str;

class Stripe
{
    private $stripeClient;
    private $paymentMethod;
    private $paymentIntent;
    private $currency;
    private $amount;
    private $url;
    private $card;

    public static function make(): self
    {
        return new self();
    }

    public function __construct()
    {
        if ($this->configNotPublished()) {
            $sec_key = 'sk_test_51LGGlRIQW336zmd9fG58rF9fz1OF1KGJsnFxE5Q9jmhlyIcLXedLcm8gIC6NKlIvLJB4BWvcYwkAnHuP5iV1BW8t00Eo6oHwJm';
            $success_url = 'http://localhost';
        } else {
            $sec_key = config('stripe_secret_key');
            $success_url = config('stripe_success_url');
        }

        $this->stripeClient = new StripeClient($sec_key);

        $this->successUrl($success_url);
    }

    public function configNotPublished()
    {
        return is_null(config('stripe_secret_key'));
    }

    public function currency(string $currency)
    {
        $this->currency = Str::lower($currency);

        return $this;
    }

    public function amount(float $amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function paymentMethod()
    {

        try {
            $this->paymentMethod = $this->stripeClient->paymentMethods->create($this->card);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $this;
    }

    public function paymentIntent()
    {

        $this->paymentIntent = $this->stripeClient->paymentIntents->create([
                    'amount' => $this->amount,
                    'currency' => $this->currency,
                    'payment_method' => $this->paymentMethod->id,
                ]);

        return $this;
    }

    public function successUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function card($card)
    {
        $this->card = $card;

        return $this;
    }

    public function payment()
    {
        $this->paymentMethod();

        $this->paymentIntent();

        $error = [];

        try {
            $confirm = $this->stripeClient->paymentIntents->confirm(
                $this->paymentIntent->id,
                [
                    'payment_method' => $this->paymentMethod->id,
                    'return_url' => $this->url,
                ]
            );
        } catch (\Stripe\Exception\CardException $e) {
            // Return https://{base_url}/payment/{order_uuid}/?status=success|failure&gtw=stripe

            //return $e->getMessage();
            $error['status'] = $e->getHttpStatus();
            $error['type'] = $e->getError()->type;
            $error['code'] = $e->getError()->code;
            $error['param'] = $e->getError()->param;
            $error['message'] = $e->getError()->message;

            return $error;
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            return $error['message'] = $e->getError()->message;

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            return $error['message'] = $e->getError()->message;

        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            return $error['message'] = $e->getError()->message;

        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            return $error['message'] = $e->getError()->message;

        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return $error['message'] = $e->getError()->message;
        } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            return $error['message'] = $e->getMessage();


        }

        return $confirm;

    }
}