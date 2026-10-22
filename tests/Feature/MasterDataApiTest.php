<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataApiTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected Category $category;
    protected Product $product;
    protected ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::create([
            'full_name' => 'Admin Test',
            'email' => 'admin@test.com',
            'phone' => '081234567890',
            'password_hash' => bcrypt('password'),
            'role' => 'seller_owner',
            'status' => 'active',
        ]);

        $this->store = Store::create([
            'owner_user_id' => $user->id,
            'name' => 'Toko Uji Coba',
            'slug' => 'toko-uji-coba',
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
            'name' => 'Kopi Arabika Gayo 250g',
            'slug' => 'kopi-arabika-gayo-250g',
            'status' => 'active',
            'weight_gram' => 250,
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'sku' => 'KOP-GAYO-01',
            'variant_name' => 'Biji Utuh (Beans)',
            'price' => 45000.00,
            'stock_quantity' => 20,
            'low_stock_threshold' => 5,
            'is_active' => true,
        ]);
    }

    public function test_can_list_products(): void
    {
        $response = $this->getJson('/api/v1/master-data/produk');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_get_categories(): void
    {
        $response = $this->getJson('/api/v1/master-data/produk/categories');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.name', 'Minuman Kopi');
    }

    public function test_can_create_product_with_variants(): void
    {
        $payload = [
            'store_id' => $this->store->id,
            'category_id' => $this->category->id,
            'name' => 'Kopi Robusta Temanggung 200g',
            'description' => 'Aroma earthy kuat dan body tebal.',
            'condition' => 'baru',
            'status' => 'active',
            'weight_gram' => 200,
            'variants' => [
                [
                    'sku' => 'KOP-ROB-01',
                    'variant_name' => 'Bubuk Halus',
                    'price' => 35000.00,
                    'stock_quantity' => 15,
                    'low_stock_threshold' => 5,
                ],
            ],
        ];

        $response = $this->postJson('/api/v1/master-data/produk', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Kopi Robusta Temanggung 200g');

        $this->assertDatabaseHas('products', [
            'name' => 'Kopi Robusta Temanggung 200g',
        ]);

        $this->assertDatabaseHas('product_variants', [
            'sku' => 'KOP-ROB-01',
            'stock_quantity' => 15,
        ]);
    }

    public function test_create_product_validation_errors(): void
    {
        $response = $this->postJson('/api/v1/master-data/produk', []);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['name', 'category_id', 'variants']]);
    }

    public function test_can_show_product(): void
    {
        $response = $this->getJson("/api/v1/master-data/produk/{$this->product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $this->product->id)
            ->assertJsonPath('data.name', $this->product->name);
    }

    public function test_can_update_product(): void
    {
        $payload = [
            'name' => 'Kopi Arabika Gayo Aceh Super 250g',
            'variants' => [
                [
                    'id' => $this->variant->id,
                    'variant_name' => 'Biji Sangrai (Medium Roast)',
                    'price' => 48000.00,
                ],
            ],
        ];

        $response = $this->putJson("/api/v1/master-data/produk/{$this->product->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Kopi Arabika Gayo Aceh Super 250g');

        $this->assertDatabaseHas('product_variants', [
            'id' => $this->variant->id,
            'price' => 48000.00,
        ]);
    }

    public function test_can_archive_product(): void
    {
        $response = $this->deleteJson("/api/v1/master-data/produk/{$this->product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'status' => 'archived',
        ]);
    }

    public function test_can_list_inventory_stock(): void
    {
        $response = $this->getJson('/api/v1/master-data/stok');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', 'aman');
    }

    public function test_can_get_inventory_summary(): void
    {
        $response = $this->getJson('/api/v1/master-data/stok/summary');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_sku', 1)
            ->assertJsonPath('data.safe_stock_count', 1)
            ->assertJsonPath('data.total_asset_value', 900000);
    }

    public function test_can_adjust_stock_in_and_out(): void
    {
        // Adjust IN (+10)
        $inResponse = $this->postJson('/api/v1/master-data/stok/adjust', [
            'variant_id' => $this->variant->id,
            'adjustment_type' => 'in',
            'quantity' => 10,
            'note' => 'Barang masuk dari supplier',
        ]);

        $inResponse->assertStatus(200)
            ->assertJsonPath('data.current_stock', 30);

        $this->assertDatabaseHas('product_variants', [
            'id' => $this->variant->id,
            'stock_quantity' => 30,
        ]);

        // Adjust OUT (-5)
        $outResponse = $this->postJson('/api/v1/master-data/stok/adjust', [
            'variant_id' => $this->variant->id,
            'adjustment_type' => 'out',
            'quantity' => 5,
            'note' => 'Barang rusak/expired',
        ]);

        $outResponse->assertStatus(200)
            ->assertJsonPath('data.current_stock', 25);

        $this->assertDatabaseHas('product_variants', [
            'id' => $this->variant->id,
            'stock_quantity' => 25,
        ]);
    }

    public function test_cannot_adjust_stock_out_below_zero(): void
    {
        $response = $this->postJson('/api/v1/master-data/stok/adjust', [
            'variant_id' => $this->variant->id,
            'adjustment_type' => 'out',
            'quantity' => 999, // exceeds available 20
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_can_get_stock_movements_history(): void
    {
        // First adjust stock to create a movement
        $this->postJson('/api/v1/master-data/stok/adjust', [
            'variant_id' => $this->variant->id,
            'adjustment_type' => 'in',
            'quantity' => 5,
        ]);

        $response = $this->getJson('/api/v1/master-data/stok/movements');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');
    }
}
