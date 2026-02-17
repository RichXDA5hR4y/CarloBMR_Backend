<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all seeders in order
        $this->call([
            // 👇 User seeder first (because other tables depend on users)
            UserSeeder::class,
            
            // 👇 Category seeder second (because products depend on categories)
            CategorySeeder::class,
            
            // 👇 Product seeder last (because it depends on categories)
            ProductSeeder::class,
        ]);
    }
}