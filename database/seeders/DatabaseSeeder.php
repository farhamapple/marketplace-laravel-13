<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $categories = ['Elektronik', 'Pakaian', 'Makanan', 'Minuman', 'Alat Tulis'];

        foreach ($categories as $name) {
            Category::factory()->create(['name' => $name]);
        }

        Product::factory(30)->recycle(Category::all())->create();
    }
}
