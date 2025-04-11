<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $users = [
            [
                'name' => 'Customer',
                'username' => 'customer',
                'email' => 'customer@sample.com',
                'role' => 'customer'
            ],
            [
                'name' => 'Seller',
                'username' => 'seller',
                'email' => 'seller@sample.com',
                'role' => 'seller'
            ],
            [
                'name' => 'Support',
                'username' => 'support',
                'email' => 'support@sample.com',
                'role' => 'support'
            ],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@sample.com',
                'role' => 'admin'
            ],
        ];

        $roles = [
            'customer' => [
                'read-products',
                'create-orders', 'read-orders', 'update-orders', 'delete-orders',
                'create-tickets', 'read-tickets', 'update-tickets', 'delete-tickets',
            ],

            'seller' => [
                'create-products', 'read-products', 'update-products', 'delete-products',
                'read-orders', 'update-orders',
            ],

            'support' => [
                'read-tickets', 'update-tickets',
            ],

            'admin' => ['all-permissions'],
        ];

        $permissions = [
            'create-users', 'read-users', 'update-users', 'delete-users',
            'create-products', 'read-products', 'update-products', 'delete-products',
            'create-orders', 'read-orders', 'update-orders', 'delete-orders',
            'create-tickets', 'read-tickets', 'update-tickets', 'delete-tickets',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            if (in_array('all-permissions', $perms)) {
                $role->givePermissionTo($permissions);
            } else {
                $role->givePermissionTo($perms);
            }
        }

        foreach ($users as $userData) {
            $user = User::factory()->create([
                'name' => $userData['name'],
                'username' => $userData['username'],
                'email' => $userData['email'],
            ]);

            $user->assignRole($userData['role']);
        }
}
}
