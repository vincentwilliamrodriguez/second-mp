<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    // This is the old version of the random order generator

    // public function definition(): array
    // {
    //     $product = Product::inRandomOrder()->first();
    //     $customer = User::where('username', 'REGEXP', '^customer[0-9]+')
    //                     ->inRandomOrder()
    //                     ->first();


    //     $isPlaced = $this->faker->boolean(80);
    //     $status = (!$isPlaced) ? 'pending' : $this->faker->randomElement(['pending', 'completed', 'cancelled']);

    //     return [
    //         'product_id' => $product->id,
    //         'customer_id' => $customer->id,
    //         'quantity' => $this->faker->numberBetween(1, $product->quantity),
    //         'is_placed' => $isPlaced,
    //         'date_placed' => $this->faker->date(),
    //         'status' => $status,
    //     ];
    // }


    // This is the new version, which passes work onto the Order Item Factory
    public function definition(): array {
        return [
            
        ];
    }
}
