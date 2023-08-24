<?php

namespace Juntyms\Stripe\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Juntyms\Stripe\Models\StripePayment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class StripePaymentFactory extends Factory
{
    protected $model = StripePayment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'payment_uuid' => fake()->uuid(),
            'response' => fake()->randomAscii(),
        ];
    }
}