<?php

namespace Database\Factories;

use App\Models\User;
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
        $seller = User::inRandomOrder()->first() ?? 1;

        return [
            'name' => $this->faker->word(),
            'seller_id' => $seller->id,
            'description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement(['Electronics', 'Books', 'Clothing', 'Home', 'Sports']),
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'picture' => $this->faker->imageUrl(640, 480, 'products', true), // or null
        ];
    }
}
