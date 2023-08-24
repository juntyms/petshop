<?php

namespace Juntyms\Stripe\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripePayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return \Juntyms\Stripe\Database\Factories\StripePaymentFactory::new();
    }
}