<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierApiTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected Supplier $supplier;

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

        $this->supplier = Supplier::create([
            'store_id' => $this->store->id,
            'name' => 'PT Biji Kopi Nusantara',
            'supply_category' => 'Biji Kopi',
            'pic_name' => 'Pak Haryanto',
            'phone' => '081299887766',
            'city' => 'Bandung',
            'status' => 'active',
        ]);
    }

    public function test_can_list_suppliers_and_categories(): void
    {
        $response = $this->getJson('/api/v1/operasional/supplier');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                     'categories',
                     'meta' => ['current_page', 'total', 'per_page'],
                 ]);

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('PT Biji Kopi Nusantara', $response->json('data.0.name'));
        $this->assertEquals('coffee', $response->json('data.0.icon_type'));
    }

    public function test_can_search_and_filter_suppliers(): void
    {
        $response = $this->getJson('/api/v1/operasional/supplier?search=Haryanto');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));

        $resCat = $this->getJson('/api/v1/operasional/supplier?category=Biji+Kopi');
        $resCat->assertStatus(200);
        $this->assertCount(1, $resCat->json('data'));
    }

    public function test_can_create_supplier(): void
    {
        $payload = [
            'name' => 'CV Kemasan Indah',
            'supply_category' => 'Cup & Kemasan',
            'pic_name' => 'Ibu Diana',
            'phone' => '081322334455',
            'email' => 'diana@kemasanindah.com',
            'city' => 'Surabaya',
            'address' => 'Jl. Rungkut Industri No. 5',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/v1/operasional/supplier', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.name', 'CV Kemasan Indah');

        $this->assertDatabaseHas('suppliers', [
            'name' => 'CV Kemasan Indah',
            'supply_category' => 'Cup & Kemasan',
        ]);
    }

    public function test_can_show_and_update_supplier(): void
    {
        $showRes = $this->getJson("/api/v1/operasional/supplier/{$this->supplier->id}");
        $showRes->assertStatus(200)
                ->assertJsonPath('data.name', 'PT Biji Kopi Nusantara');

        $updateRes = $this->putJson("/api/v1/operasional/supplier/{$this->supplier->id}", [
            'name' => 'PT Biji Kopi Nusantara Abadi',
            'supply_category' => 'Biji Kopi & Bubuk',
            'pic_name' => 'Pak Haryanto Senior',
            'phone' => '081299887766',
            'city' => 'Bandung Barat',
            'status' => 'active',
        ]);

        $updateRes->assertStatus(200)
                  ->assertJsonPath('success', true)
                  ->assertJsonPath('data.name', 'PT Biji Kopi Nusantara Abadi');

        $this->assertDatabaseHas('suppliers', [
            'id' => $this->supplier->id,
            'name' => 'PT Biji Kopi Nusantara Abadi',
        ]);
    }

    public function test_can_delete_supplier(): void
    {
        $response = $this->deleteJson("/api/v1/operasional/supplier/{$this->supplier->id}");
        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('suppliers', [
            'id' => $this->supplier->id,
        ]);
    }
}
