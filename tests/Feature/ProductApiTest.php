<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $this->product = Product::factory()->create([
            'name' => 'Laptop Gaming',
            'category_id' => $this->category->id,
            'price' => 15000000,
            'stock' => 10,
            'sold' => 5,
        ]);
    }

    public function test_list_products(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta', 'links']);
    }

    public function test_list_products_pagination(): void
    {
        Product::factory()->count(20)->create();

        $response = $this->getJson('/api/v1/products?per_page=5');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_list_products_search(): void
    {
        $response = $this->getJson('/api/v1/products?search=Laptop');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Laptop Gaming', $response->json('data.0.name'));
    }

    public function test_list_products_filter_by_category(): void
    {
        $cat2 = Category::factory()->create(['slug' => 'pakaian']);
        Product::factory()->create(['category_id' => $cat2->id, 'name' => 'Baju']);

        $response = $this->getJson('/api/v1/products?category=elektronik');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Laptop Gaming', $response->json('data.0.name'));
    }

    public function test_show_product(): void
    {
        $response = $this->getJson('/api/v1/products/' . $this->product->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Laptop Gaming')
            ->assertJsonPath('data.price', 15000000)
            ->assertJsonPath('data.stock', 10)
            ->assertJsonPath('data.sold', 5)
            ->assertJsonStructure(['data' => ['category' => ['id', 'name', 'slug']]]);
    }

    public function test_show_product_not_found(): void
    {
        $response = $this->getJson('/api/v1/products/99999');

        $response->assertStatus(404);
    }

    public function test_list_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_list_categories_with_counts(): void
    {
        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200);
        $elektronik = collect($response->json('data'))->firstWhere('slug', 'elektronik');
        $this->assertNotNull($elektronik);
        $this->assertEquals(1, $elektronik['products_count']);
    }

    public function test_product_image_url_is_full_url(): void
    {
        $product = Product::factory()->create([
            'image' => 'products/test.jpg',
        ]);

        $response = $this->getJson('/api/v1/products/' . $product->id);

        $response->assertStatus(200);
        $this->assertStringContainsString('/storage/products/test.jpg', $response->json('data.image'));
    }

    public function test_product_image_is_null_when_empty(): void
    {
        $product = Product::factory()->create(['image' => null]);

        $response = $this->getJson('/api/v1/products/' . $product->id);

        $response->assertStatus(200);
        $this->assertNull($response->json('data.image'));
    }

    public function test_products_endpoints_are_public(): void
    {
        $this->getJson('/api/v1/products')->assertStatus(200);
        $this->getJson('/api/v1/products/' . $this->product->id)->assertStatus(200);
        $this->getJson('/api/v1/categories')->assertStatus(200);
    }
}
