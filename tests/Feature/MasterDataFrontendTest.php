<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataFrontendTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->store = Store::create([
            'owner_user_id' => $this->user->id,
            'name' => 'Toko Kopi Kenangan Amikom',
            'slug' => 'toko-kopi-kenangan-amikom',
            'status' => 'active',
        ]);

        $this->category = Category::create([
            'name' => 'Kopi & Minuman',
            'slug' => 'kopi-minuman',
            'is_active' => true,
        ]);
    }

    public function test_product_page_renders_with_alpine_and_modals(): void
    {
        $response = $this->get('/admin/products');

        $response->assertStatus(200);
        $response->assertSee('productManagement()', false);
        $response->assertSee('modalCreateOpen', false);
        $response->assertSee('modalEditOpen', false);
        $response->assertSee('modalDeleteOpen', false);
        $response->assertSee('/api/v1/master-data/produk', false);
    }

    public function test_inventory_page_renders_with_alpine_and_modals(): void
    {
        $response = $this->get('/admin/inventory');

        $response->assertStatus(200);
        $response->assertSee('inventoryManagement()', false);
        $response->assertSee('modalAdjustOpen', false);
        $response->assertSee('modalHistoryOpen', false);
        $response->assertSee('/api/v1/master-data/stok', false);
        $response->assertSee('/api/v1/master-data/stok/summary', false);
    }

    public function test_pos_cashier_page_renders_with_alpine_and_modals(): void
    {
        $response = $this->get('/admin/pos');

        $response->assertStatus(200);
        $response->assertSee('posCashier()', false);
        $response->assertSee('modalPaymentOpen', false);
        $response->assertSee('modalReceiptOpen', false);
        $response->assertSee('printable-thermal-receipt', false);
        $response->assertSee('/api/v1/operasional/kasir', false);
    }

    public function test_online_orders_page_renders_with_alpine_and_modals(): void
    {
        $response = $this->get('/admin/orders');

        $response->assertStatus(200);
        $response->assertSee('ordersManagement()', false);
        $response->assertSee('modalShipOpen', false);
        $response->assertSee('modalDetailOpen', false);
        $response->assertSee('modalCancelOpen', false);
        $response->assertSee('/api/v1/operasional/pesanan-online', false);
    }

    public function test_promo_page_renders_with_alpine_and_modals(): void
    {
        $response = $this->get('/admin/promo');

        $response->assertStatus(200);
        $response->assertSee('promoManagement()', false);
        $response->assertSee('modalOpen', false);
        $response->assertSee('modalDeleteOpen', false);
        $response->assertSee('/api/v1/operasional/promo-diskon', false);
    }
}
