<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelangganApiTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $seller = User::factory()->create(['role' => 'seller_owner']);

        $this->store = Store::create([
            'owner_user_id' => $seller->id,
            'name' => 'Toko Amikom Utama',
            'slug' => 'toko-amikom-utama',
            'phone' => '081234567890',
            'email' => 'toko@amikom.ac.id',
            'is_active' => true,
        ]);

        $this->customer = User::create([
            'full_name' => 'Andi Saputra',
            'email' => 'andi@gmail.com',
            'phone' => '081234567890',
            'password_hash' => bcrypt('secret123'),
            'role' => 'customer',
            'status' => 'active',
            'addresses' => [['address' => 'Jl. Magelang KM 5, Sleman', 'is_default' => true]],
        ]);

        Order::create([
            'checkout_number' => 'CHK-TEST-001',
            'order_number' => 'ORD-TEST-001',
            'store_id' => $this->store->id,
            'buyer_user_id' => $this->customer->id,
            'status' => 'delivered',
            'subtotal_amount' => 1200000,
            'total_amount' => 1200000,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'shipping_address' => ['recipient_name' => 'Andi Saputra', 'phone' => '081234567890'],
            'placed_at' => now(),
        ]);
    }

    public function test_can_list_customers_with_metrics(): void
    {
        $response = $this->getJson('/api/v1/operasional/pelanggan');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                     'metrics' => ['total_customers', 'new_this_month', 'vip_count'],
                     'meta' => ['current_page', 'total', 'per_page'],
                 ]);

        $this->assertEquals(1, $response->json('metrics.total_customers'));
        $this->assertEquals(1, $response->json('metrics.vip_count'));
        $this->assertEquals('Gold', $response->json('data.0.tier'));
    }

    public function test_can_search_customers(): void
    {
        $response = $this->getJson('/api/v1/operasional/pelanggan?search=Andi');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Andi Saputra', $response->json('data.0.full_name'));

        $resEmpty = $this->getJson('/api/v1/operasional/pelanggan?search=NonExistent');
        $resEmpty->assertStatus(200);
        $this->assertCount(0, $resEmpty->json('data'));
    }

    public function test_can_create_customer(): void
    {
        $payload = [
            'full_name' => 'Bambang Sudirman',
            'phone' => '085812345678',
            'email' => 'bambang@gmail.com',
            'address' => 'Jl. Kaliurang KM 9, Sleman',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/v1/operasional/pelanggan', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.full_name', 'Bambang Sudirman');

        $this->assertDatabaseHas('users', [
            'email' => 'bambang@gmail.com',
            'role' => 'customer',
        ]);
    }

    public function test_can_show_and_update_customer(): void
    {
        $showRes = $this->getJson("/api/v1/operasional/pelanggan/{$this->customer->id}");
        $showRes->assertStatus(200)
                ->assertJsonPath('data.full_name', 'Andi Saputra')
                ->assertJsonPath('data.total_orders', 1);

        $updateRes = $this->putJson("/api/v1/operasional/pelanggan/{$this->customer->id}", [
            'full_name' => 'Andi Saputra Wijaya',
            'phone' => '081299998888',
            'email' => 'andi.new@gmail.com',
            'address' => 'Jl. Gejayan No. 10',
            'status' => 'active',
        ]);

        $updateRes->assertStatus(200)
                  ->assertJsonPath('success', true)
                  ->assertJsonPath('data.full_name', 'Andi Saputra Wijaya');

        $this->assertDatabaseHas('users', [
            'id' => $this->customer->id,
            'full_name' => 'Andi Saputra Wijaya',
            'phone' => '081299998888',
        ]);
    }

    public function test_can_delete_customer(): void
    {
        $custToDelete = User::create([
            'full_name' => 'Pelanggan Dihapus',
            'email' => 'hapus@gmail.com',
            'password_hash' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $response = $this->deleteJson("/api/v1/operasional/pelanggan/{$custToDelete->id}");
        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('users', [
            'id' => $custToDelete->id,
        ]);
    }
}
