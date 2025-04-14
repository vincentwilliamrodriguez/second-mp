<?php

namespace Database\Seeders;

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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('orders')->delete();
        DB::table('products')->delete();
        DB::table('users')->delete();

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
