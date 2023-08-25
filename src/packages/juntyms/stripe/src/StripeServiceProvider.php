<?php

namespace Juntyms\Stripe;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (! File::exists(config_path('stripe.php'))) {
            $this->publishes([
                __DIR__ . '/../config/stripe.php' => config_path('stripe.php'),
            ], 'stripe-config');
        }


        if ($this->app->runningInConsole()) {
            // Export the migration
            if (! class_exists('CreateStripePaymentsTable')) {
                $this->publishes([
                  __DIR__ . '/../database/migrations/create_stripe_payments_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_stripe_payments_table.php'),
                  // you can add any number of migrations here
                ], 'stripe-migration');
            }
        }
    }

    public function register()
    {
        $this->app->singleton('stripe', function ($app) {
            return new Stripe();
        });
    }
}
