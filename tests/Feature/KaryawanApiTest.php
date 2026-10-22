<?php

namespace Tests\Feature;

use App\Models\PosShift;
use App\Models\Store;
use App\Models\StoreMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KaryawanApiTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $owner;
    protected User $cashier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create([
            'full_name' => 'Bagas Owner',
            'email' => 'owner@umkmgo.id',
            'role' => 'seller_owner',
            'status' => 'active',
        ]);

        $this->store = Store::create([
            'owner_user_id' => $this->owner->id,
            'name' => 'Toko Amikom Utama',
            'slug' => 'toko-amikom-utama',
            'phone' => '081234567890',
            'email' => 'toko@amikom.ac.id',
            'status' => 'active',
        ]);

        StoreMember::create([
            'store_id' => $this->store->id,
            'user_id' => $this->owner->id,
            'role' => 'seller_owner',
            'is_active' => true,
            'joined_at' => now(),
        ]);

        $this->cashier = User::factory()->create([
            'full_name' => 'Budi Kasir',
            'email' => 'budi@umkmgo.id',
            'phone' => '081211112222',
            'role' => 'cashier',
            'status' => 'active',
            'notification_prefs' => ['shift' => 'pagi'],
        ]);

        StoreMember::create([
            'store_id' => $this->store->id,
            'user_id' => $this->cashier->id,
            'role' => 'cashier',
            'is_active' => true,
            'joined_at' => now(),
        ]);
    }

    public function test_can_list_employees_with_summary(): void
    {
        $response = $this->getJson('/api/v1/operasional/karyawan');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data',
                'summary' => [
                    'total_employees',
                    'shift_pagi',
                    'shift_malam',
                    'active_shifts',
                ],
            ]);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_filter_employees_by_search_and_status(): void
    {
        $response = $this->getJson('/api/v1/operasional/karyawan?search=Budi');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Budi Kasir', $response->json('data.0.name'));

        $inactiveUser = User::factory()->create([
            'full_name' => 'Doni Nonaktif',
            'email' => 'doni@umkmgo.id',
            'role' => 'seller_staff',
            'status' => 'inactive',
        ]);

        StoreMember::create([
            'store_id' => $this->store->id,
            'user_id' => $inactiveUser->id,
            'role' => 'seller_staff',
            'is_active' => false,
            'joined_at' => now(),
        ]);

        $responseActive = $this->getJson('/api/v1/operasional/karyawan?status=active');
        $responseActive->assertStatus(200);
        $this->assertCount(2, $responseActive->json('data'));

        $responseInactive = $this->getJson('/api/v1/operasional/karyawan?status=inactive');
        $responseInactive->assertStatus(200);
        $this->assertCount(1, $responseInactive->json('data'));
    }

    public function test_can_create_employee(): void
    {
        $payload = [
            'full_name' => 'Siti Nurhaliza',
            'email' => 'siti@umkmgo.id',
            'phone' => '081233445566',
            'role' => 'cashier',
            'shift' => 'malam',
            'is_active' => true,
            'password' => 'secret123',
        ];

        $response = $this->postJson('/api/v1/operasional/karyawan', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Siti Nurhaliza')
            ->assertJsonPath('data.shift_key', 'malam');

        $this->assertDatabaseHas('users', [
            'email' => 'siti@umkmgo.id',
            'role' => 'cashier',
        ]);

        $this->assertDatabaseHas('store_members', [
            'store_id' => $this->store->id,
            'role' => 'cashier',
            'is_active' => true,
        ]);
    }

    public function test_can_show_employee(): void
    {
        $response = $this->getJson('/api/v1/operasional/karyawan/' . $this->cashier->id);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Budi Kasir')
            ->assertJsonPath('data.role', 'cashier');
    }

    public function test_can_update_employee(): void
    {
        $payload = [
            'full_name' => 'Budi Pratama Update',
            'email' => 'budi@umkmgo.id',
            'phone' => '081299998888',
            'role' => 'manager',
            'shift' => 'malam',
            'is_active' => true,
        ];

        $response = $this->putJson('/api/v1/operasional/karyawan/' . $this->cashier->id, $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Budi Pratama Update')
            ->assertJsonPath('data.role_key', 'manager')
            ->assertJsonPath('data.shift_key', 'malam');

        $this->assertDatabaseHas('users', [
            'id' => $this->cashier->id,
            'full_name' => 'Budi Pratama Update',
            'role' => 'manager',
        ]);
    }

    public function test_can_assign_shift_and_open_pos_shift(): void
    {
        $payload = [
            'shift' => 'malam',
            'open_pos_shift' => true,
            'register_name' => 'Kasir Reg #2',
            'opening_cash' => 250000,
            'note' => 'Shift malam siap buka',
        ];

        $response = $this->postJson('/api/v1/operasional/karyawan/' . $this->cashier->id . '/assign-shift', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.shift_key', 'malam');

        $this->assertNotNull($response->json('data.pos_shift_id'));

        $this->assertDatabaseHas('pos_shifts', [
            'cashier_user_id' => $this->cashier->id,
            'register_name' => 'Kasir Reg #2',
            'status' => 'open',
        ]);
    }

    public function test_can_list_shifts(): void
    {
        PosShift::create([
            'store_id' => $this->store->id,
            'register_name' => 'Kasir Reg #1',
            'cashier_user_id' => $this->cashier->id,
            'opening_cash' => 150000,
            'status' => 'open',
            'opened_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/operasional/karyawan/shifts');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertNotEmpty($response->json('data'));
        $this->assertEquals('Kasir Reg #1', $response->json('data.0.register_name'));
    }

    public function test_can_delete_employee_and_prevent_owner_deletion(): void
    {
        $responseOwner = $this->deleteJson('/api/v1/operasional/karyawan/' . $this->owner->id);
        $responseOwner->assertStatus(422);

        $responseCashier = $this->deleteJson('/api/v1/operasional/karyawan/' . $this->cashier->id);
        $responseCashier->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('store_members', [
            'store_id' => $this->store->id,
            'user_id' => $this->cashier->id,
        ]);
    }
}
