<?php

namespace Juntyms\Stripe\Tests;

use Juntyms\Stripe\StripeServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
          StripeServiceProvider::class,
        ];
    }


}