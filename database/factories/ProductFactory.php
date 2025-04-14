<?php

namespace Database\Factories;

use App\Http\Controllers\ProductController;
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
        $seller = User::where('username', 'REGEXP', '^seller[0-9]+')
                        ->inRandomOrder()
                        ->first();

        return [
            'name' => $this->faker->word(),
            'seller_id' => $seller->id,
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(ProductController::class::$categories),
            'quantity' => $this->faker->numberBetween(1, 20),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'picture' => $this->faker->imageUrl(640, 480, 'products', true),
        ];
    }
}
