<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\StoreMember;
use App\Models\User;
use App\Services\RbacService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RbacAndAuthTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $owner;
    protected User $cashier;
    protected User $warehouseStaff;
    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::create([
            'full_name' => 'Adam Bagas',
            'email' => 'adam@umkmgo.id',
            'phone' => '081234567890',
            'password_hash' => Hash::make('password'),
            'role' => 'seller_owner',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->store = Store::create([
            'owner_user_id' => $this->owner->id,
            'name' => 'Toko Kopi & Fashion Andi',
            'slug' => 'toko-kopi-fashion-andi',
            'status' => 'active',
        ]);

        StoreMember::create([
            'store_id' => $this->store->id,
            'user_id' => $this->owner->id,
            'role' => 'seller_owner',
            'permissions' => ['*'],
            'is_active' => true,
        ]);

        $this->cashier = User::create([
            'full_name' => 'Siti Kasir',
            'email' => 'siti@umkmgo.id',
            'phone' => '081234567891',
            'password_hash' => Hash::make('password'),
            'role' => 'cashier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        StoreMember::create([
            'store_id' => $this->store->id,
            'user_id' => $this->cashier->id,
            'role' => 'cashier',
            'permissions' => RbacService::defaultPresets()['cashier']['permissions'],
            'is_active' => true,
        ]);

        $this->warehouseStaff = User::create([
            'full_name' => 'Budi Gudang',
            'email' => 'gudang@umkmgo.id',
            'phone' => '081234567892',
            'password_hash' => Hash::make('password'),
            'role' => 'seller_staff',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        StoreMember::create([
            'store_id' => $this->store->id,
            'user_id' => $this->warehouseStaff->id,
            'role' => 'seller_staff',
            'permissions' => RbacService::defaultPresets()['seller_staff']['permissions'],
            'is_active' => true,
        ]);

        $this->manager = User::create([
            'full_name' => 'Rudi Manajer',
            'email' => 'manajer@umkmgo.id',
            'phone' => '081234567893',
            'password_hash' => Hash::make('password'),
            'role' => 'manager',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        StoreMember::create([
            'store_id' => $this->store->id,
            'user_id' => $this->manager->id,
            'role' => 'manager',
            'permissions' => RbacService::defaultPresets()['manager']['permissions'],
            'is_active' => true,
        ]);
    }

    public function test_login_page_renders_cleanly_for_production(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('UMKMGo');
        $response->assertSee('Email Akun');
        $response->assertSee('Kata Sandi');
        $response->assertDontSee('Simulasi Akun Demo RBAC');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'adam@umkmgo.id',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->owner);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'adam@umkmgo.id',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $this->actingAs($this->owner);

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_owner_has_full_access_to_all_three_flows(): void
    {
        $this->actingAs($this->owner);

        // Flow 1
        $this->get('/admin/products')->assertStatus(200);
        $this->get('/admin/inventory')->assertStatus(200);

        // Flow 2
        $this->get('/admin/pos')->assertStatus(200);
        $this->get('/admin/orders')->assertStatus(200);
        $this->get('/admin/customers')->assertStatus(200);
        $this->get('/admin/promo')->assertStatus(200);

        // Flow 3
        $this->get('/admin/finance')->assertStatus(200);
        $this->get('/admin/reports')->assertStatus(200);
        $this->get('/admin/employees')->assertStatus(200);
        $this->get('/admin/settings')->assertStatus(200);
    }

    public function test_cashier_can_access_flow2_pos_and_orders_but_denied_from_flow1_and_flow3(): void
    {
        $this->actingAs($this->cashier);

        // Allowed in Flow 2 (Cashier Preset)
        $this->get('/admin/pos')->assertStatus(200);
        $this->get('/admin/orders')->assertStatus(200);
        $this->get('/admin/customers')->assertStatus(200);
        $this->get('/admin/promo')->assertStatus(200);

        // Denied in Flow 1 (Katalog & Inventaris)
        $this->get('/admin/products')->assertStatus(403)->assertSee('403 - Akses Dibatasi');
        $this->get('/admin/inventory')->assertStatus(403);

        // Denied in Flow 3 (Keuangan & Pengaturan)
        $this->get('/admin/finance')->assertStatus(403);
        $this->get('/admin/reports')->assertStatus(403);
        $this->get('/admin/employees')->assertStatus(403);
        $this->get('/admin/settings')->assertStatus(403);
    }

    public function test_warehouse_staff_can_access_flow1_and_shipping_but_denied_from_finance_and_settings(): void
    {
        $this->actingAs($this->warehouseStaff);

        // Allowed in Flow 1 & Orders (Warehouse Preset)
        $this->get('/admin/products')->assertStatus(200);
        $this->get('/admin/inventory')->assertStatus(200);
        $this->get('/admin/orders')->assertStatus(200);
        $this->get('/admin/suppliers')->assertStatus(200);

        // Denied from POS Kasir and Flow 3 Management
        $this->get('/admin/pos')->assertStatus(403);
        $this->get('/admin/finance')->assertStatus(403);
        $this->get('/admin/employees')->assertStatus(403);
        $this->get('/admin/settings')->assertStatus(403);
    }

    public function test_custom_permission_matrix_via_karyawan_api_grants_dynamic_access(): void
    {
        $this->actingAs($this->owner);

        // Cashier initially cannot access products
        $this->actingAs($this->cashier);
        $this->get('/admin/products')->assertStatus(403);

        // Owner grants 'products.view' to Cashier via Custom Matrix update
        $this->actingAs($this->owner);
        $updatedPermissions = array_merge(
            RbacService::defaultPresets()['cashier']['permissions'],
            ['products.view']
        );

        $response = $this->putJson("/api/v1/operasional/karyawan/{$this->cashier->id}", [
            'full_name' => 'Siti Kasir Senior',
            'email' => 'siti@umkmgo.id',
            'role' => 'cashier',
            'permissions' => $updatedPermissions,
            'is_active' => true,
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);

        // Now Cashier can access products!
        $this->actingAs($this->cashier);
        $this->get('/admin/products')->assertStatus(200);
    }

    public function test_api_permissions_endpoint_returns_correct_groups_and_presets(): void
    {
        $this->actingAs($this->owner);

        $response = $this->getJson('/api/v1/operasional/karyawan/permissions');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'groups',
                    'presets',
                    'all_keys',
                ],
            ]);
    }

    public function test_api_endpoints_return_403_json_when_permission_missing(): void
    {
        // Cashier tries to adjust stock (requires inventory.manage)
        $this->actingAs($this->cashier);

        $response = $this->postJson('/api/v1/master-data/stok/adjust', [
            'variant_id' => 'dummy',
            'adjustment_type' => 'add',
            'quantity' => 10,
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('success', false)
            ->assertSee('Akses ditolak');
    }
}
