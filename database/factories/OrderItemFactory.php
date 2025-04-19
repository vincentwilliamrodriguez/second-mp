<?php

namespace Database\Factories;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {

        $product = Product::inRandomOrder()->first();
        $status = $this->faker->randomElement(['pending', 'accepted', 'shipped', 'delivered', 'cancelled']);

        $datePlaced = Carbon::now()->subDays(rand(15, 40));
        $dateAccepted = (in_array($status, ['accepted', 'shipped', 'delivered'])) ? (clone $datePlaced)->addDays(rand(1, 2)) : null;
        $dateShipped = (in_array($status, ['shipped', 'delivered'])) ?              (clone $dateAccepted)->addDays(rand(1, 4)) : null;
        $dateDelivered = ($status === 'delivered') ?                                (clone $dateShipped)->addDays(rand(1, 14)) : null;

        return [
            'product_id' => $product->id,
            'quantity' => $this->faker->numberBetween(1, $product->quantity),
            'date_placed' => $datePlaced,
            'date_accepted' => $dateAccepted,
            'date_shipped' => $dateShipped,
            'date_delivered' => $dateDelivered,
            'status' => $status,
        ];
    }



    // 'order_id',
    // 'product_id',
    // 'quantity',
    // 'date_placed',
    // 'date_accepted',
    // 'date_shipped',
    // 'date_delivered',
    // 'status',
}
