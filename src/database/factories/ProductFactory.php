<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $catuid = Category::inRandomOrder()->first()->uuid;

        //dd($catuid);

        return [
            'category_uuid' => fake()->randomElement(Category::all())['uuid'],
            'uuid' => fake()->uuid(),
            'title' => fake()->word(),
            'price' => fake()->randomFloat(2, 1, 1500),
            'description' => fake()->sentence(),
            'metadata' => [
                'brand' => fake()->randomElement(Brand::all())['uuid'],
                'image' => fake()->uuid()
            ]
        ];
    }
}
