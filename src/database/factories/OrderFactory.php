<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'user_id' => fake()->randomElement(User::all())['id'],
            'order_status_id' => fake()->randomElement(OrderStatus::all())['id'],
            'payment_id' => fake()->randomElement(Payment::all())['id'],
            'uuid' => fake()->uuid,
            'products' => [
                'product' => fake()->randomElement(Product::all())['id'],
                'quantity' => fake()->randomDigit()
            ],
            'address' => [
                'billing' => fake()->address(),
                'shipping' => fake()->address()
            ],
            'delivery_fee' => fake()->randomFloat(2, 1, 1500),
            'amount' => fake()->randomFloat(2, 1, 1500),
            'shipped_at' => fake()->date()

        ];
    }
}
