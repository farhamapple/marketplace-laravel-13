<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTransactionApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::factory()->create();

        $this->user = User::factory()->create(['role' => 'customer']);

        $this->product = Product::factory()->create([
            'name' => 'Test Product',
            'category_id' => $category->id,
            'price' => 50000,
            'stock' => 10,
            'sold' => 0,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password',
            'device_name' => 'test',
        ]);

        $this->token = $response->json('token');
    }

    protected function headers(): array
    {
        return ['Authorization' => 'Bearer ' . $this->token];
    }

    public function test_add_to_cart(): void
    {
        $response = $this->postJson('/api/v1/cart', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ], $this->headers());

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'product', 'quantity', 'subtotal']])
            ->assertJsonPath('data.quantity', 2)
            ->assertJsonPath('data.product.id', $this->product->id);
    }

    public function test_add_to_cart_insufficient_stock(): void
    {
        $this->product->update(['stock' => 1]);

        $response = $this->postJson('/api/v1/cart', [
            'product_id' => $this->product->id,
            'quantity' => 5,
        ], $this->headers());

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'INSUFFICIENT_STOCK');
    }

    public function test_add_to_cart_increments_existing(): void
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->postJson('/api/v1/cart', [
            'product_id' => $this->product->id,
            'quantity' => 3,
        ], $this->headers());

        $response->assertStatus(201);
        $this->assertEquals(4, $response->json('data.quantity'));
    }

    public function test_list_cart(): void
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->getJson('/api/v1/cart', $this->headers());

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta' => ['total_items', 'total_price']])
            ->assertJsonPath('meta.total_items', 1)
            ->assertJsonPath('meta.total_price', 100000);
    }

    public function test_update_cart_quantity(): void
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->patchJson('/api/v1/cart/' . $cart->id, [
            'quantity' => 5,
        ], $this->headers());

        $response->assertStatus(200)
            ->assertJsonPath('data.quantity', 5);
    }

    public function test_update_cart_forbidden_other_user(): void
    {
        $other = User::factory()->create();
        $cart = Cart::create([
            'user_id' => $other->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->patchJson('/api/v1/cart/' . $cart->id, [
            'quantity' => 2,
        ], $this->headers());

        $response->assertStatus(403);
    }

    public function test_remove_from_cart(): void
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->deleteJson('/api/v1/cart/' . $cart->id, [], $this->headers());

        $response->assertStatus(204);
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_checkout(): void
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->postJson('/api/v1/cart/checkout', [], $this->headers());

        $response->assertStatus(201)
            ->assertJsonStructure(['data', 'message']);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'type' => 'sale',
        ]);

        $this->product->refresh();
        $this->assertEquals(8, $this->product->stock);
        $this->assertEquals(2, $this->product->sold);

        $this->assertDatabaseMissing('carts', ['user_id' => $this->user->id]);
    }

    public function test_checkout_empty_cart(): void
    {
        $response = $this->postJson('/api/v1/cart/checkout', [], $this->headers());

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'EMPTY_CART');
    }

    public function test_checkout_insufficient_stock(): void
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 99,
        ]);

        $response = $this->postJson('/api/v1/cart/checkout', [], $this->headers());

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'INSUFFICIENT_STOCK');
    }

    public function test_transaction_history(): void
    {
        Transaction::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'type' => 'sale',
            'quantity' => 1,
            'total' => 50000,
        ]);

        $response = $this->getJson('/api/v1/transactions', $this->headers());

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta', 'links'])
            ->assertJsonPath('meta.total', 1);
    }

    public function test_transaction_history_filter_type(): void
    {
        Transaction::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'type' => 'sale',
            'quantity' => 1,
            'total' => 50000,
        ]);

        $response = $this->getJson('/api/v1/transactions?type=purchase', $this->headers());

        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 0);
    }

    public function test_transaction_detail(): void
    {
        $tx = Transaction::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'type' => 'sale',
            'quantity' => 1,
            'total' => 50000,
        ]);

        $response = $this->getJson('/api/v1/transactions/' . $tx->id, $this->headers());

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $tx->id);
    }

    public function test_transaction_detail_forbidden(): void
    {
        $other = User::factory()->create();
        $tx = Transaction::create([
            'user_id' => $other->id,
            'product_id' => $this->product->id,
            'type' => 'sale',
            'quantity' => 1,
            'total' => 50000,
        ]);

        $response = $this->getJson('/api/v1/transactions/' . $tx->id, $this->headers());

        $response->assertStatus(403);
    }

    public function test_cart_requires_auth(): void
    {
        $this->getJson('/api/v1/cart')->assertStatus(401);
        $this->postJson('/api/v1/cart')->assertStatus(401);
        $this->postJson('/api/v1/cart/checkout')->assertStatus(401);
        $this->getJson('/api/v1/transactions')->assertStatus(401);
    }
}
