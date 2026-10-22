<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PosSale;
use App\Models\PosShift;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanApiTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'seller_owner',
            'status' => 'active',
        ]);

        $this->store = Store::create([
            'owner_user_id' => $this->user->id,
            'name' => 'Toko Amikom Utama',
            'slug' => 'toko-amikom-utama',
            'phone' => '081234567890',
            'email' => 'toko@amikom.ac.id',
            'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Minuman',
            'slug' => 'minuman',
            'is_active' => true,
        ]);

        $product = Product::create([
            'store_id' => $this->store->id,
            'category_id' => $category->id,
            'name' => 'Kopi Gula Aren',
            'slug' => 'kopi-gula-aren',
            'weight_gram' => 250,
            'status' => 'active',
        ]);

        $order = Order::create([
            'store_id' => $this->store->id,
            'order_number' => '#ORD-TEST-1',
            'checkout_number' => 'CHK-TEST-1',
            'buyer_user_id' => $this->user->id,
            'shipping_address' => ['name' => 'Budi Buyer', 'phone' => '0812345678', 'address' => 'Jl. Ring Road Utara'],
            'subtotal_amount' => 40000,
            'shipping_amount' => 10000,
            'discount_amount' => 0,
            'total_amount' => 50000,
            'status' => 'completed',
            'courier' => 'JNE',
            'placed_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name_snapshot' => 'Kopi Gula Aren',
            'unit_price' => 20000,
            'quantity' => 2,
            'line_total' => 40000,
        ]);

        $shift = PosShift::create([
            'store_id' => $this->store->id,
            'register_name' => 'Kasir 1',
            'cashier_user_id' => $this->user->id,
            'opening_cash' => 100000,
            'status' => 'open',
            'opened_at' => now(),
        ]);

        PosSale::create([
            'store_id' => $this->store->id,
            'shift_id' => $shift->id,
            'cashier_user_id' => $this->user->id,
            'receipt_number' => 'POS-20261022-9999',
            'items' => [
                ['name' => 'Kopi Gula Aren', 'price' => 20000, 'qty' => 1, 'subtotal' => 20000],
            ],
            'payments' => [
                ['method' => 'qris', 'amount' => 20000],
            ],
            'subtotal_amount' => 20000,
            'total_amount' => 20000,
            'status' => 'completed',
            'sold_at' => now(),
        ]);
    }

    public function test_can_get_sales_reports_summary_with_charts_and_kpis(): void
    {
        $response = $this->getJson('/api/v1/laporan/penjualan');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'summary' => [
                    'total_omzet',
                    'total_omzet_formatted',
                    'growth_percentage',
                    'period',
                    'period_label',
                    'total_transactions',
                ],
                'chart' => [
                    'points',
                    'trend_path',
                    'area_path',
                    'axis_labels',
                ],
                'top_products',
                'payments',
            ]);

        $this->assertEquals(70000, $response->json('summary.total_omzet'));
        $this->assertEquals(2, $response->json('summary.total_transactions'));
        $this->assertNotEmpty($response->json('top_products'));
        $this->assertEquals('Kopi Gula Aren', $response->json('top_products.0.name'));
    }

    public function test_can_get_reports_for_different_periods(): void
    {
        $periods = ['today', '7_days', '30_days', 'last_month', 'this_year'];

        foreach ($periods as $period) {
            $response = $this->getJson('/api/v1/laporan/penjualan?period=' . $period);
            $response->assertStatus(200)
                ->assertJsonPath('success', true)
                ->assertJsonPath('summary.period', $period);
        }
    }

    public function test_can_export_sales_report_csv(): void
    {
        $response = $this->get('/api/v1/laporan/penjualan/export-csv');

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/csv'));
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition'), 'attachment; filename='));

        $content = $response->streamedContent();
        $this->assertStringContainsString('Tanggal', $content);
        $this->assertStringContainsString('#ORD-TEST-1', $content);
        $this->assertStringContainsString('POS-20261022-9999', $content);
    }
}
