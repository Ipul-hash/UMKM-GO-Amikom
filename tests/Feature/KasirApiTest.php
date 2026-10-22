<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirApiTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $cashier;
    protected Category $category;
    protected Product $product;
    protected ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cashier = User::factory()->create([
            'role' => 'seller_owner',
            'full_name' => 'Budi Kasir',
        ]);

        $this->store = Store::create([
            'owner_user_id' => $this->cashier->id,
            'name' => 'Amikom Coffee & Bakery',
            'slug' => 'amikom-coffee-bakery',
            'status' => 'active',
        ]);

        $this->category = Category::create([
            'name' => 'Minuman Kopi',
            'slug' => 'minuman-kopi',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'store_id' => $this->store->id,
            'category_id' => $this->category->id,
            'name' => 'Coffee Americano',
            'slug' => 'coffee-americano',
            'status' => 'active',
            'images' => ['/images/dashboard/coffee-americano.jpg'],
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'sku' => 'POS-COF-001',
            'variant_name' => 'Cup 250ml',
            'price' => 15000,
            'stock_quantity' => 20,
            'is_active' => true,
        ]);
    }

    public function test_can_fetch_pos_catalog(): void
    {
        $response = $this->getJson('/api/v1/operasional/kasir/catalog');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonFragment([
            'name' => 'Coffee Americano',
            'price' => 15000,
            'stock_quantity' => 20,
        ]);
    }

    public function test_can_fetch_pos_customers(): void
    {
        $response = $this->getJson('/api/v1/operasional/kasir/customers');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonFragment([
            'full_name' => 'Pelanggan Umum',
        ]);
    }

    public function test_can_get_or_create_active_pos_shift(): void
    {
        $response = $this->getJson('/api/v1/operasional/kasir/shift?store_id=' . $this->store->id);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.status', 'open');
    }

    public function test_can_checkout_pos_with_cash_and_calculate_change(): void
    {
        $payload = [
            'store_id' => $this->store->id,
            'items' => [
                [
                    'variant_id' => $this->variant->id,
                    'quantity' => 2,
                    'unit_price' => 15000,
                ],
            ],
            'discount_amount' => 5000,
            'tax_amount' => 0,
            'payment_method' => 'cash',
            'amount_paid' => 30000, // Total = 25.000, Change = 5.000
            'note' => 'Bawa pulang',
        ];

        $response = $this->postJson('/api/v1/operasional/kasir/checkout', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.subtotal', 30000);
        $response->assertJsonPath('data.discount', 5000);
        $response->assertJsonPath('data.total', 25000);
        $response->assertJsonPath('data.amount_paid', 30000);
        $response->assertJsonPath('data.change', 5000);
        $response->assertJsonPath('data.payment_method', 'CASH');

        // Stock must be decremented: 20 - 2 = 18
        $this->assertDatabaseHas('product_variants', [
            'id' => $this->variant->id,
            'stock_quantity' => 18,
        ]);

        // Stock movement must be recorded
        $this->assertDatabaseHas('stock_movements', [
            'variant_id' => $this->variant->id,
            'movement_type' => 'out',
            'quantity_delta' => -2,
            'quantity_after' => 18,
        ]);

        // Sale and financial entry must exist
        $receiptNumber = $response->json('data.receipt_number');
        $this->assertDatabaseHas('pos_sales', [
            'receipt_number' => $receiptNumber,
            'total_amount' => 25000,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('financial_entries', [
            'direction' => 'income',
            'amount' => 25000,
        ]);
    }

    public function test_can_checkout_pos_with_qris(): void
    {
        $payload = [
            'store_id' => $this->store->id,
            'items' => [
                [
                    'variant_id' => $this->variant->id,
                    'quantity' => 1,
                    'unit_price' => 15000,
                ],
            ],
            'payment_method' => 'qris',
            'amount_paid' => 15000,
        ];

        $response = $this->postJson('/api/v1/operasional/kasir/checkout', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.payment_method', 'QRIS');
        $response->assertJsonPath('data.change', 0);
    }

    public function test_checkout_fails_if_stock_is_insufficient(): void
    {
        $payload = [
            'store_id' => $this->store->id,
            'items' => [
                [
                    'variant_id' => $this->variant->id,
                    'quantity' => 999, // Stock is only 20
                    'unit_price' => 15000,
                ],
            ],
            'payment_method' => 'cash',
            'amount_paid' => 15000000,
        ];

        $response = $this->postJson('/api/v1/operasional/kasir/checkout', $payload);

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
        $response->assertSee('Stok tidak mencukupi');
    }

    public function test_checkout_fails_if_cash_underpaid(): void
    {
        $payload = [
            'store_id' => $this->store->id,
            'items' => [
                [
                    'variant_id' => $this->variant->id,
                    'quantity' => 2,
                    'unit_price' => 15000,
                ],
            ],
            'payment_method' => 'cash',
            'amount_paid' => 10000, // Total is 30.000
        ];

        $response = $this->postJson('/api/v1/operasional/kasir/checkout', $payload);

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
        $response->assertSee('kurang');
    }

    public function test_can_fetch_receipt_by_receipt_number(): void
    {
        // 1. Checkout first
        $checkoutResponse = $this->postJson('/api/v1/operasional/kasir/checkout', [
            'store_id' => $this->store->id,
            'items' => [
                ['variant_id' => $this->variant->id, 'quantity' => 1, 'unit_price' => 15000],
            ],
            'payment_method' => 'cash',
            'amount_paid' => 20000,
        ]);

        $receiptNumber = $checkoutResponse->json('data.receipt_number');

        // 2. Fetch receipt
        $response = $this->getJson('/api/v1/operasional/kasir/receipt/' . $receiptNumber);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.receipt_number', $receiptNumber);
        $response->assertJsonPath('data.total', 15000);
        $response->assertJsonPath('data.amount_paid', 20000);
        $response->assertJsonPath('data.change', 5000);
    }
}
