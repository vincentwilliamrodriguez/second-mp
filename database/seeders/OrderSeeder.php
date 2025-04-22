<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder {
    // This is the new version of the OrderSeeder, which handles both Orders and OrderItems
    public function run(): void {
        $customers = User::where('username', 'REGEXP', '^customer[0-9]+')->get();
        $ordersNum = rand(2, 4);
        $orderItemsNum = rand(1, 7);

        $allProductIds = Product::pluck('id')->toArray();

        foreach ($customers as $customer) {
            for ($i = 0; $i < $ordersNum; $i++) {
                $order = Order::factory()->create([
                    'customer_id' => $customer->id,
                    'full_name' => $customer->name
                ]);


                $availableProductIds = $allProductIds;
                $subtotal = 0;

                for ($j = 0; $j < min($orderItemsNum, count($availableProductIds)); $j++) {
                    $randomKey = array_rand($availableProductIds);
                    $productId = $availableProductIds[$randomKey];
                    unset($availableProductIds[$randomKey]);

                    $orderItem = OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                    ]);

                    $subtotal += $orderItem['product_price'] * $orderItem['order_quantity'];
                }


                $shippingFee = match ($order->delivery_method) {
                    'standard' => 20,
                    'express' => 30,
                    'same_day' => 50,
                    default => 20,
                };


                $tax = round($subtotal * 0.12, 2);
                $total = round($subtotal + $shippingFee + $tax, 2);

                $order->update([
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'tax' => $tax,
                    'total_amount' => $total,
                ]);

            }
        }
    }


    // This is the old version

    // public function run(): void {
    //     Order::factory()->count(20)->create();
    // }
}
