<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PesanOnlineApiTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $seller;
    protected User $buyer;
    protected Product $product;
    protected ProductVariant $variant;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create(['role' => 'seller_owner']);
        $this->buyer = User::factory()->create(['role' => 'buyer', 'full_name' => 'Budi Pembeli']);

        $this->store = Store::create([
            'owner_user_id' => $this->seller->id,
            'name' => 'Toko Amikom Online',
            'slug' => 'toko-amikom-online',
            'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'store_id' => $this->store->id,
            'category_id' => $category->id,
            'name' => 'Kopi Tubruk',
            'slug' => 'kopi-tubruk',
            'status' => 'active',
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'sku' => 'KOP-001',
            'variant_name' => '250gr',
            'price' => 25000,
            'stock_quantity' => 50,
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'checkout_number' => 'CHK-' . strtoupper(Str::random(8)),
            'order_number' => '#ORD-9999',
            'buyer_user_id' => $this->buyer->id,
            'store_id' => $this->store->id,
            'status' => 'pending',
            'shipping_address' => [
                'recipient_name' => 'Budi Pembeli',
                'phone' => '08123456789',
                'address_line1' => 'Jl. Ring Road Utara, Condongcatur',
                'city' => 'Sleman',
            ],
            'subtotal_amount' => 50000,
            'shipping_amount' => 10000,
            'discount_amount' => 0,
            'service_fee' => 1000,
            'total_amount' => 61000,
            'buyer_note' => 'Mohon dikemas rapi',
            'courier' => 'JNE',
            'placed_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'variant_id' => $this->variant->id,
            'product_name_snapshot' => 'Kopi Tubruk (250gr)',
            'unit_price' => 25000,
            'quantity' => 2,
            'line_total' => 50000,
        ]);
    }

    public function test_can_fetch_orders_list_with_tab_counts(): void
    {
        $response = $this->getJson('/api/v1/operasional/pesanan-online?store_id=' . $this->store->id);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure([
            'data',
            'counts' => ['all', 'new', 'processing', 'shipped', 'delivered', 'cancelled'],
            'meta',
        ]);
        $response->assertJsonFragment([
            'order_number' => '#ORD-9999',
            'buyer_name' => 'Budi Pembeli',
        ]);
    }

    public function test_can_accept_and_process_order(): void
    {
        $response = $this->postJson("/api/v1/operasional/pesanan-online/{$this->order->id}/accept");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.status', 'processing');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'processing',
        ]);
    }

    public function test_can_ship_order_with_courier_and_tracking_number(): void
    {
        // First accept
        $this->order->update(['status' => 'processing']);

        $payload = [
            'courier' => 'J&T Express',
            'service_name' => 'EZ Reguler',
            'tracking_number' => 'JT1234567890',
        ];

        $response = $this->postJson("/api/v1/operasional/pesanan-online/{$this->order->id}/ship", $payload);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.status', 'shipped');
        $response->assertJsonPath('data.courier', 'J&T Express');
        $response->assertJsonPath('data.tracking_number', 'JT1234567890');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'shipped',
            'courier' => 'J&T Express',
            'tracking_number' => 'JT1234567890',
        ]);
    }

    public function test_can_complete_delivered_order(): void
    {
        $this->order->update(['status' => 'shipped']);

        $response = $this->postJson("/api/v1/operasional/pesanan-online/{$this->order->id}/complete");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.status', 'delivered');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'delivered',
        ]);
    }

    public function test_can_cancel_order_and_restore_stock(): void
    {
        $initialStock = $this->variant->stock_quantity; // 50

        $response = $this->postJson("/api/v1/operasional/pesanan-online/{$this->order->id}/cancel", [
            'cancel_reason' => 'Stok kemasan habis',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.status', 'cancelled');

        // Stock restored: 50 + 2 = 52
        $this->assertDatabaseHas('product_variants', [
            'id' => $this->variant->id,
            'stock_quantity' => $initialStock + 2,
        ]);
    }

    public function test_can_fetch_courier_options(): void
    {
        $response = $this->getJson('/api/v1/operasional/pesanan-online/couriers');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonFragment(['code' => 'JNE']);
    }
}
