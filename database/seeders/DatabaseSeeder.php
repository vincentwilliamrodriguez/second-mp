<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        OrderItem::truncate();
        Order::truncate();
        Product::truncate();
        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $superadmin = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@sample.com',
            'number' => '09123456789',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->call([
            RoleSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
        ]);

        $superadmin->assignRole('admin');
    }
}
