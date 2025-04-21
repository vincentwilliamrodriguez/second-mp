<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder {
    // This is the new version of the OrderSeeder, which handles both Orders and OrderItems
    public function run(): void {
        $customers = User::where('username', 'REGEXP', '^customer[0-9]+')->get();
        $ordersNum = rand(2, 4);
        $orderItemsNum = rand(1, 7);

        foreach ($customers as $customer) {
            for ($i = 0; $i < $ordersNum; $i++) {
                $order = Order::factory()->create(['customer_id' => $customer->id]);

                for ($j = 0; $j < $orderItemsNum; $j++) {
                    OrderItem::factory()->create(['order_id' => $order->id]);
                }
            }
        }
    }


    // This is the old version

    // public function run(): void {
    //     Order::factory()->count(20)->create();
    // }
}
