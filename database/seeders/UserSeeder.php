<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user (update if exists)
        User::updateOrCreate(
            ['email' => 'admin@carlobmr.com'], // Unique key
            [
                'name' => 'Admin Carlo BMR',
                'email' => 'admin@carlobmr.com',
                'password' => bcrypt('password'),
                'phone' => '081234567890',
                'role' => 'admin'
            ]
        );

        // Create cashier user (update if exists)
        User::updateOrCreate(
            ['email' => 'cashier@carlobmr.com'],
            [
                'name' => 'Cashier 1',
                'email' => 'cashier@carlobmr.com',
                'password' => bcrypt('password'),
                'phone' => '081234567891',
                'role' => 'cashier'
            ]
        );

        // Create sample customer (update if exists)
        User::updateOrCreate(
            ['email' => 'customer@carlobmr.com'],
            [
                'name' => 'Customer 1',
                'email' => 'customer@carlobmr.com',
                'password' => bcrypt('password'),
                'phone' => '081234567892',
                'role' => 'customer'
            ]
        );
    }
}