<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category_id' => Category::factory(),
            'stock' => fake()->numberBetween(0, 100),
            'sold' => fake()->numberBetween(0, 200),
            'price' => fake()->numberBetween(10, 500) * 1000,
        ];
    }
}
