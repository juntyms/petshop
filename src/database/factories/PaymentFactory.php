<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->randomElement(User::all())['uuid'],
            'type' => fake()->randomElement(['credit_card', 'cash_on_delivery','bank_transfer']),
            'details' => [
                'holder_name' => fake()->firstName(),
                'number' => fake()->creditCardNumber(),
                'ccv' => fake()->numerify(),
                'expire_date' => fake()->creditCardExpirationDateString()
            ]
        ];
    }
}
