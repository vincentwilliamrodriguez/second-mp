<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sourcePath = public_path('images/sample-products');
        $destinationPath = storage_path('app/public/products');


        $sampleProducts = [
            [
                'name' => '1984',
                'category' => 'Books',
                'price' => 399.00,
            ],
            [
                'name' => 'Classic Denim Jacket',
                'category' => 'Clothing',
                'price' => 1299.50,
            ],
            [
                'name' => 'Wireless Bluetooth Earbuds',
                'category' => 'Electronics',
                'price' => 1799.99,
            ],
            [
                'name' => 'Coffee Table',
                'category' => 'Furniture',
                'price' => 3499.75,
            ],
            [
                'name' => 'Heavy-Duty Power Drill',
                'category' => 'Hardware',
                'price' => 2599.00,
            ],
            [
                'name' => 'Vitamin C Tablets',
                'category' => 'Health',
                'price' => 549.25,
            ],
            [
                'name' => 'Watercolor Painting Kit',
                'category' => 'Hobbies',
                'price' => 799.95,
            ],
            [
                'name' => 'Foldable Camping Chair',
                'category' => 'Other',
                'price' => 1199.00,
            ],
        ];



        foreach ($sampleProducts as $productDetails) {
            $product = Product::factory()->create();
            $product->fill($productDetails);
            $product->picture = 'products/' . $product->name . '.png';
            $product->save();


            $sourceFile = $sourcePath . '/' . $product->name . '.png';
            $destinationFile = $destinationPath . '/' . $product->name . '.png';

            if (File::exists($sourceFile)) {
                File::copy($sourceFile, $destinationFile);
            }
        }

        Product::factory()->count(3)->create();
    }
}
