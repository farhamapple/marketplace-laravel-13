<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
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
        $categoryMap = [];

        foreach ($categories as $name) {
            $cat = Category::create(['name' => $name]);
            $categoryMap[$name] = $cat->id;
        }

        $products = [
            ['Laptop ThinkPad', 'Elektronik', 15, 42],
            ['Mouse Wireless', 'Elektronik', 30, 87],
            ['Kemeja Premium', 'Pakaian', 25, 63],
            ['Jaket Hoodie', 'Pakaian', 20, 38],
            ['Kopi Arabika', 'Makanan', 50, 120],
            ['Roti Tawar', 'Makanan', 40, 95],
            ['Air Mineral Galon', 'Minuman', 60, 150],
            ['Jus Jeruk', 'Minuman', 35, 78],
            ['Pulpen Gel', 'Alat Tulis', 100, 200],
            ['Buku Notes', 'Alat Tulis', 45, 112],
        ];

        foreach ($products as [$name, $catName, $stock, $sold]) {
            Product::create([
                'name' => $name,
                'category_id' => $categoryMap[$catName],
                'stock' => $stock,
                'sold' => $sold,
                'price' => rand(10, 500) * 1000,
            ]);
        }
    }
}
