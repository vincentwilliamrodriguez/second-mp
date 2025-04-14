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
        $product = Product::inRandomOrder()->first();
        $customer = User::where('username', 'REGEXP', '^customer[0-9]+')
                        ->inRandomOrder()
                        ->first();


        $isPlaced = $this->faker->boolean(80);
        $status = (!$isPlaced) ? 'pending' : $this->faker->randomElement(['pending', 'completed', 'cancelled']);

        return [
            'product_id' => $product->id,
            'customer_id' => $customer->id,
            'quantity' => $this->faker->numberBetween(1, $product->quantity),
            'is_placed' => $isPlaced,
            'date_placed' => $this->faker->date(),
            'status' => $status,
        ];
    }
}
