<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);

        $this->customer = User::factory()->create([
            'name' => 'Budi',
            'email' => 'budi@test.com',
            'role' => 'customer',
        ]);
    }

    protected function authenticate(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => 'password',
            'device_name' => 'test',
        ]);

        $this->token = $response->json('token');
    }

    protected function authHeaders(): array
    {
        return ['Authorization' => 'Bearer ' . $this->token];
    }

    public function test_register(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Siti',
            'email' => 'siti@test.com',
            'password' => 'Password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name', 'email'], 'token']);
    }

    public function test_register_validation_error(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => '',
            'email' => 'not-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['error' => ['code', 'message', 'details']]);
    }

    public function test_login(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->customer->email,
            'password' => 'password',
            'device_name' => 'test',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'name', 'email'], 'token']);
    }

    public function test_login_invalid_credentials(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->customer->email,
            'password' => 'wrong-password',
            'device_name' => 'test',
        ]);

        $response->assertStatus(422);
    }

    public function test_me(): void
    {
        $this->authenticate();

        $response = $this->getJson('/api/v1/auth/me', $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['data' => ['email' => $this->admin->email]]);
    }

    public function test_me_unauthenticated(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    public function test_logout(): void
    {
        $this->authenticate();

        $response = $this->postJson('/api/v1/auth/logout', [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['message' => 'Berhasil logout.']);
    }

    public function test_list_customers(): void
    {
        $this->authenticate();
        User::factory()->count(5)->create(['role' => 'customer']);

        $response = $this->getJson('/api/v1/customers', $this->authHeaders());

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta', 'links']);
    }

    public function test_list_customers_pagination(): void
    {
        $this->authenticate();
        User::factory()->count(20)->create(['role' => 'customer']);

        $response = $this->getJson('/api/v1/customers?per_page=5', $this->authHeaders());

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
        $this->assertEquals(5, $response->json('meta.per_page'));
    }

    public function test_list_customers_search(): void
    {
        $this->authenticate();
        User::factory()->create(['name' => 'Target Name', 'role' => 'customer']);

        $response = $this->getJson('/api/v1/customers?search=Target', $this->authHeaders());

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_list_customers_sort(): void
    {
        $this->authenticate();
        User::factory()->create(['name' => 'Aaaa', 'role' => 'customer']);
        User::factory()->create(['name' => 'Zzzz', 'role' => 'customer']);

        $response = $this->getJson('/api/v1/customers?sort=-name', $this->authHeaders());

        $response->assertStatus(200);
        $this->assertEquals('Zzzz', $response->json('data.0.name'));
    }

    public function test_create_customer(): void
    {
        $this->authenticate();

        $response = $this->postJson('/api/v1/customers', [
            'name' => 'New Customer',
            'email' => 'new@test.com',
            'password' => 'Password123',
        ], $this->authHeaders());

        $response->assertStatus(201)
            ->assertJson(['data' => ['name' => 'New Customer']]);
    }

    public function test_create_customer_duplicate_email(): void
    {
        $this->authenticate();

        $response = $this->postJson('/api/v1/customers', [
            'name' => 'Duplicate',
            'email' => $this->customer->email,
            'password' => 'Password123',
        ], $this->authHeaders());

        $response->assertStatus(422)
            ->assertJsonStructure(['error' => ['code', 'message', 'details']]);
    }

    public function test_show_customer(): void
    {
        $this->authenticate();

        $response = $this->getJson('/api/v1/customers/' . $this->customer->id, $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['data' => ['email' => 'budi@test.com']]);
    }

    public function test_show_customer_not_found(): void
    {
        $this->authenticate();

        $response = $this->getJson('/api/v1/customers/99999', $this->authHeaders());

        $response->assertStatus(404);
    }

    public function test_update_customer(): void
    {
        $this->authenticate();

        $response = $this->putJson('/api/v1/customers/' . $this->customer->id, [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
        ], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'Updated Name']]);
    }

    public function test_partial_update_customer(): void
    {
        $this->authenticate();

        $response = $this->patchJson('/api/v1/customers/' . $this->customer->id, [
            'name' => 'Patched Name',
        ], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'Patched Name']]);
        $this->assertEquals('budi@test.com', $response->json('data.email'));
    }

    public function test_delete_customer(): void
    {
        $this->authenticate();
        $target = User::factory()->create(['role' => 'customer']);

        $response = $this->deleteJson('/api/v1/customers/' . $target->id, [], $this->authHeaders());

        $response->assertStatus(204);
        $this->assertSoftDeleted($target);
    }

    public function test_delete_customer_not_found(): void
    {
        $this->authenticate();

        $response = $this->deleteJson('/api/v1/customers/99999', [], $this->authHeaders());

        $response->assertStatus(404);
    }
}
