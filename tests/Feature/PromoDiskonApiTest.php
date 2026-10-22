<?php

namespace Tests\Feature;

use App\Models\Promotion;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoDiskonApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = \App\Models\User::factory()->create(['role' => 'seller_owner']);

        $store = Store::create([
            'owner_user_id' => $user->id,
            'name' => 'Toko Amikom Utama',
            'slug' => 'toko-amikom-utama',
            'phone' => '081234567890',
            'email' => 'toko@amikom.ac.id',
            'address' => 'Jl. Ring Road Utara, Condongcatur',
            'is_active' => true,
        ]);

        Promotion::create([
            'store_id' => $store->id,
            'name' => 'Diskon Weekend 20%',
            'description' => 'Spesial weekend potongan 20%',
            'code' => 'WEEKEND20',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'max_discount_amount' => 15000,
            'minimum_purchase' => 50000,
            'usage_limit' => 100,
            'per_user_limit' => 1,
            'used_count' => 45,
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->addDays(7),
            'status' => 'active',
            'created_at' => now(),
        ]);

        Promotion::create([
            'store_id' => $store->id,
            'name' => 'Potongan Kopi Rp 5.000',
            'description' => 'Potongan langsung 5rb',
            'code' => 'KOPIHEMAT',
            'discount_type' => 'fixed',
            'discount_value' => 5000,
            'minimum_purchase' => 25000,
            'usage_limit' => 100,
            'used_count' => 100,
            'starts_at' => now()->subDays(20),
            'ends_at' => now()->subDays(5),
            'status' => 'expired',
            'created_at' => now()->subDays(20),
        ]);

        Promotion::create([
            'store_id' => $store->id,
            'name' => 'Promo Diskon Off',
            'description' => 'Promo nonaktif',
            'code' => 'PROMOOFF',
            'discount_type' => 'fixed',
            'discount_value' => 3000,
            'minimum_purchase' => 15000,
            'usage_limit' => 50,
            'used_count' => 5,
            'starts_at' => now()->subDays(1),
            'ends_at' => now()->addDays(3),
            'status' => 'disabled',
            'created_at' => now()->subDays(1),
        ]);
    }

    public function test_can_list_promotions_with_counts(): void
    {
        $response = $this->getJson('/api/v1/operasional/promo-diskon');

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                     'counts' => ['all', 'active', 'expired', 'disabled'],
                     'meta' => ['current_page', 'total', 'per_page'],
                 ]);

        $this->assertEquals(3, $response->json('counts.all'));
        $this->assertEquals(1, $response->json('counts.active'));
        $this->assertEquals(1, $response->json('counts.expired'));
        $this->assertEquals(1, $response->json('counts.disabled'));
    }

    public function test_can_filter_promotions_by_status_and_search(): void
    {
        $resActive = $this->getJson('/api/v1/operasional/promo-diskon?status=active');
        $resActive->assertStatus(200);
        $this->assertCount(1, $resActive->json('data'));
        $this->assertEquals('WEEKEND20', $resActive->json('data.0.code'));

        $resSearch = $this->getJson('/api/v1/operasional/promo-diskon?search=KOPIHEMAT');
        $resSearch->assertStatus(200);
        $this->assertCount(1, $resSearch->json('data'));
        $this->assertEquals('KOPIHEMAT', $resSearch->json('data.0.code'));
    }

    public function test_can_create_new_promotion(): void
    {
        $payload = [
            'name' => 'Diskon Kilat 50%',
            'code' => 'FLASH50',
            'discount_type' => 'percentage',
            'discount_value' => 50,
            'max_discount_amount' => 25000,
            'minimum_purchase' => 40000,
            'usage_limit' => 200,
            'per_user_limit' => 1,
            'starts_at' => now()->format('Y-m-d H:i:s'),
            'ends_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'description' => 'Promo flash sale spesial',
        ];

        $response = $this->postJson('/api/v1/operasional/promo-diskon', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.code', 'FLASH50');

        $this->assertDatabaseHas('promotions', [
            'code' => 'FLASH50',
            'name' => 'Diskon Kilat 50%',
        ]);
    }

    public function test_can_show_and_update_promotion(): void
    {
        $promo = Promotion::where('code', 'WEEKEND20')->first();

        $showResponse = $this->getJson("/api/v1/operasional/promo-diskon/{$promo->id}");
        $showResponse->assertStatus(200)
                     ->assertJsonPath('data.code', 'WEEKEND20');

        $updateResponse = $this->putJson("/api/v1/operasional/promo-diskon/{$promo->id}", [
            'name' => 'Diskon Weekend Super 25%',
            'code' => 'WEEKEND25',
            'discount_type' => 'percentage',
            'discount_value' => 25,
            'max_discount_amount' => 20000,
            'minimum_purchase' => 60000,
            'usage_limit' => 150,
            'per_user_limit' => 2,
            'status' => 'active',
        ]);

        $updateResponse->assertStatus(200)
                       ->assertJsonPath('success', true)
                       ->assertJsonPath('data.code', 'WEEKEND25');

        $this->assertDatabaseHas('promotions', [
            'id' => $promo->id,
            'code' => 'WEEKEND25',
            'discount_value' => '25.00',
        ]);
    }

    public function test_can_toggle_promotion_status(): void
    {
        $promo = Promotion::where('code', 'WEEKEND20')->first();
        $this->assertEquals('active', $promo->status);

        $response = $this->patchJson("/api/v1/operasional/promo-diskon/{$promo->id}/toggle-status");
        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'disabled');

        $this->assertEquals('disabled', $promo->fresh()->status);

        $responseReactivate = $this->patchJson("/api/v1/operasional/promo-diskon/{$promo->id}/toggle-status");
        $responseReactivate->assertStatus(200)
                           ->assertJsonPath('data.status', 'active');

        $this->assertEquals('active', $promo->fresh()->status);
    }

    public function test_can_delete_promotion(): void
    {
        $promo = Promotion::where('code', 'PROMOOFF')->first();

        $response = $this->deleteJson("/api/v1/operasional/promo-diskon/{$promo->id}");
        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('promotions', [
            'id' => $promo->id,
        ]);
    }
}
