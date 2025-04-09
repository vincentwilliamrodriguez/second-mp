<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
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
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $customer = User::inRandomOrder()->first() ?? 1;

        return [
            'product_id' => $product->id,
            'customer_id' => $customer->id,
            'quantity' => $this->faker->numberBetween(1, 10),
            'is_placed' => $this->faker->boolean(),
            'date_placed' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }
}
