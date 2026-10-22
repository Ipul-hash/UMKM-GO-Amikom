<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user_and_store_with_uuid(): void
    {
        $user = User::create([
            'full_name' => 'Pak Budi UMKM',
            'email' => 'budi@umkmgo.id',
            'phone' => '081234567890',
            'password_hash' => bcrypt('password123'),
            'role' => 'seller_owner',
            'status' => 'active',
        ]);

        $this->assertNotNull($user->id);
        $this->assertTrue(Str::isUuid($user->id));

        $store = Store::create([
            'owner_user_id' => $user->id,
            'name' => 'Toko Berkah Snack',
            'slug' => 'toko-berkah-snack',
            'status' => 'active',
            'latitude' => -7.4243123,
            'longitude' => 109.2301234,
        ]);

        $this->assertNotNull($store->id);
        $this->assertTrue(Str::isUuid($store->id));
        $this->assertEquals($user->id, $store->owner->id);

        $category = Category::create([
            'name' => 'Makanan & Minuman',
            'slug' => 'makanan-minuman',
        ]);

        $this->assertNotNull($category->id);
        $this->assertTrue(Str::isUuid($category->id));

        $product = Product::create([
            'store_id' => $store->id,
            'category_id' => $category->id,
            'name' => 'Keripik Tempe Purwokerto 200g',
            'slug' => 'keripik-tempe-purwokerto-200g',
            'weight_gram' => 250,
            'status' => 'active',
        ]);

        $this->assertNotNull($product->id);
        $this->assertTrue(Str::isUuid($product->id));

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'KRP-TMP-01',
            'variant_name' => 'Rasa Original',
            'price' => 15000.00,
            'stock_quantity' => 100,
        ]);

        $this->assertNotNull($variant->id);
        $this->assertTrue(Str::isUuid($variant->id));
        $this->assertEquals(15000.00, (float) $variant->price);
        $this->assertEquals('KRP-TMP-01', $variant->sku);
        $this->assertEquals($product->id, $variant->product->id);
    }
}
