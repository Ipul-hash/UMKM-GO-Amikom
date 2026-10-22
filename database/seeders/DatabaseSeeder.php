<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FinancialEntry;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PosSale;
use App\Models\PosShift;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\StoreMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete realistic dummy data.
     */
    public function run(): void
    {
        // 1. Users
        $owner = User::create([
            'full_name' => 'Adam Bagas',
            'email' => 'adam@umkmgo.id',
            'phone' => '081234567890',
            'password_hash' => Hash::make('password'),
            'role' => 'seller_owner',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $cashier = User::create([
            'full_name' => 'Siti Kasir',
            'email' => 'siti@umkmgo.id',
            'phone' => '081234567891',
            'password_hash' => Hash::make('password'),
            'role' => 'cashier',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $customer1 = User::create([
            'full_name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'phone' => '081398765432',
            'password_hash' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
            'addresses' => [
                [
                    'label' => 'Rumah',
                    'receiver_name' => 'Budi Santoso',
                    'phone' => '081398765432',
                    'address' => 'Jl. HR Bunyamin No. 102, Purwokerto Utara',
                    'city' => 'Banyumas',
                    'postal_code' => '53121',
                ]
            ],
        ]);

        $customer2 = User::create([
            'full_name' => 'Rina Wulandari',
            'email' => 'rina@gmail.com',
            'phone' => '081512345678',
            'password_hash' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        $customer3 = User::create([
            'full_name' => 'Dewi Lestari',
            'email' => 'dewi@gmail.com',
            'phone' => '081788990011',
            'password_hash' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        // 2. Store
        $store = Store::create([
            'owner_user_id' => $owner->id,
            'name' => 'Toko Kopi & Fashion Andi',
            'slug' => 'toko-kopi-fashion-andi',
            'description' => 'Menyediakan aneka minuman kopi artisanal, cemilan lezat, serta busana kasual dan produk kerajinan khas UMKM.',
            'phone' => '081234567890',
            'email' => 'store@kopifashionandi.com',
            'business_type' => 'Kafe & Retail Fashion',
            'status' => 'active',
            'address_line' => 'Jl. Prof. Dr. Soeparno No. 45, Arcawinangun',
            'province' => 'Jawa Tengah',
            'city' => 'Banyumas',
            'district' => 'Purwokerto Timur',
            'postal_code' => '53123',
            'latitude' => -7.4243123,
            'longitude' => 109.2301234,
            'opening_hours' => [
                'senin_jumat' => '08:00 - 22:00',
                'sabtu_minggu' => '08:00 - 23:00',
            ],
            'verified_at' => now(),
        ]);

        // Store Memberships
        StoreMember::create([
            'store_id' => $store->id,
            'user_id' => $owner->id,
            'role' => 'seller_owner',
            'is_active' => true,
            'joined_at' => now(),
        ]);

        StoreMember::create([
            'store_id' => $store->id,
            'user_id' => $cashier->id,
            'role' => 'cashier',
            'is_active' => true,
            'joined_at' => now(),
        ]);

        // 3. Categories
        $catFnb = Category::create([
            'name' => 'Makanan & Minuman',
            'slug' => 'makanan-minuman',
            'description' => 'Aneka kopi, minuman segar, dan kudapan lezat.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catFashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Pakaian kasual, kaos polos, dan aksesoris harian.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catCraft = Category::create([
            'name' => 'Kerajinan & Produk Lokal',
            'slug' => 'kerajinan-produk-lokal',
            'description' => 'Produk anyaman bambu dan kerajinan lokal berkualitas.',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 4. Products & Variants
        // Product 1: Americano
        $p1 = Product::create([
            'store_id' => $store->id,
            'category_id' => $catFnb->id,
            'name' => 'Coffe Americano',
            'slug' => 'coffe-americano',
            'description' => 'Kopi hitam klasik dari biji kopi arabika pilihan dengan aroma kuat dan rasa seimbang.',
            'condition' => 'baru',
            'status' => 'active',
            'weight_gram' => 300,
            'images' => ['/images/dashboard/coffee-americano.jpg'],
        ]);

        $v1 = ProductVariant::create([
            'product_id' => $p1->id,
            'sku' => 'KOP-AMER-01',
            'variant_name' => 'Dingin (Iced)',
            'price' => 15000.00,
            'compare_at_price' => 18000.00,
            'stock_quantity' => 45,
            'low_stock_threshold' => 10,
            'is_active' => true,
        ]);

        // Product 2: Matcha Latte
        $p2 = Product::create([
            'store_id' => $store->id,
            'category_id' => $catFnb->id,
            'name' => 'Matcha Latte',
            'slug' => 'matcha-latte',
            'description' => 'Perpaduan bubuk matcha Jepang murni dengan susu segar krimi.',
            'condition' => 'baru',
            'status' => 'active',
            'weight_gram' => 300,
            'images' => ['/images/dashboard/matcha-latte.jpg'],
        ]);

        $v2 = ProductVariant::create([
            'product_id' => $p2->id,
            'sku' => 'KOP-MATC-01',
            'variant_name' => 'Dingin (Iced)',
            'price' => 15000.00,
            'stock_quantity' => 30,
            'low_stock_threshold' => 5,
            'is_active' => true,
        ]);

        // Product 3: Fries
        $p3 = Product::create([
            'store_id' => $store->id,
            'category_id' => $catFnb->id,
            'name' => 'Fries',
            'slug' => 'fries-crispy',
            'description' => 'Kentang goreng renyah bumbu gurih istimewa.',
            'condition' => 'baru',
            'status' => 'active',
            'weight_gram' => 200,
            'images' => ['/images/dashboard/fries.jpg'],
        ]);

        $v3 = ProductVariant::create([
            'product_id' => $p3->id,
            'sku' => 'SNK-FRIE-01',
            'variant_name' => 'Porsi Reguler',
            'price' => 22000.00,
            'stock_quantity' => 25,
            'low_stock_threshold' => 5,
            'is_active' => true,
        ]);

        // Product 4: Tas Anyaman
        $p4 = Product::create([
            'store_id' => $store->id,
            'category_id' => $catCraft->id,
            'name' => 'Tas Anyaman',
            'slug' => 'tas-anyaman-bambu',
            'description' => 'Tas belanja etnik anyaman bambu dan rotan ramah lingkungan.',
            'condition' => 'baru',
            'status' => 'active',
            'weight_gram' => 400,
            'images' => ['/images/dashboard/tas-anyaman.jpg'],
        ]);

        $v4 = ProductVariant::create([
            'product_id' => $p4->id,
            'sku' => 'KRJ-TASY-01',
            'variant_name' => 'Ukuran Sedang (M)',
            'price' => 45000.00,
            'stock_quantity' => 14,
            'low_stock_threshold' => 5,
            'is_active' => true,
        ]);

        // Product 5: T-Shirt (Stok Menipis)
        $p5 = Product::create([
            'store_id' => $store->id,
            'category_id' => $catFashion->id,
            'name' => 'T-Shirt',
            'slug' => 't-shirt-cotton-combed',
            'description' => 'Kaos polos katun combed 30s sejuk dan nyaman dipakai sehari-hari.',
            'condition' => 'baru',
            'status' => 'active',
            'weight_gram' => 250,
            'images' => ['/images/dashboard/tshirt.jpg'],
        ]);

        $v5 = ProductVariant::create([
            'product_id' => $p5->id,
            'sku' => 'FSH-TSHR-01',
            'variant_name' => 'Hitam - XL',
            'price' => 25000.00,
            'stock_quantity' => 4, // low stock!
            'low_stock_threshold' => 10,
            'is_active' => true,
        ]);

        $v6 = ProductVariant::create([
            'product_id' => $p5->id,
            'sku' => 'FSH-TSHR-02',
            'variant_name' => 'Putih - L',
            'price' => 25000.00,
            'stock_quantity' => 3, // low stock!
            'low_stock_threshold' => 10,
            'is_active' => true,
        ]);

        // 5. Stock Movements
        StockMovement::create([
            'store_id' => $store->id,
            'variant_id' => $v1->id,
            'movement_type' => 'in',
            'quantity_delta' => 50,
            'quantity_after' => 50,
            'reference_type' => 'manual_adjustment',
            'note' => 'Stok awal persediaan',
            'created_by' => $owner->id,
            'created_at' => now()->subDays(3),
        ]);

        // 6. Promotions
        Promotion::create([
            'store_id' => $store->id,
            'name' => 'Promo Kopi Hari Ini',
            'code' => 'KOPIASIK',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'max_discount_amount' => 10000.00,
            'minimum_purchase' => 30000.00,
            'usage_limit' => 100,
            'per_user_limit' => 2,
            'used_count' => 14,
            'status' => 'active',
            'starts_at' => now()->subDays(1),
            'ends_at' => now()->addDays(7),
        ]);

        // 7. Orders & Order Items
        // Order 1 (#ORD-0092)
        $ord1 = Order::create([
            'checkout_number' => 'CHK-' . strtoupper(Str::random(10)),
            'order_number' => '#ORD-0092',
            'buyer_user_id' => $customer1->id,
            'store_id' => $store->id,
            'status' => 'pending',
            'shipping_address' => [
                'name' => 'Budi Santoso',
                'phone' => '081398765432',
                'address' => 'Jl. HR Bunyamin No. 102, Purwokerto',
            ],
            'subtotal_amount' => 30000.00,
            'shipping_amount' => 10000.00,
            'discount_amount' => 0.00,
            'total_amount' => 40000.00,
            'courier' => 'GrabExpress',
            'service_name' => 'Instant',
            'placed_at' => now()->subMinutes(15),
        ]);

        OrderItem::create([
            'order_id' => $ord1->id,
            'product_id' => $p1->id,
            'variant_id' => $v1->id,
            'product_name_snapshot' => 'Coffe Americano',
            'variant_snapshot' => ['variant' => 'Dingin (Iced)'],
            'sku_snapshot' => 'KOP-AMER-01',
            'unit_price' => 15000.00,
            'quantity' => 2,
            'discount_amount' => 0.00,
            'line_total' => 30000.00,
        ]);

        // Order 2 (#ORD-0091)
        $ord2 = Order::create([
            'checkout_number' => 'CHK-' . strtoupper(Str::random(10)),
            'order_number' => '#ORD-0091',
            'buyer_user_id' => $customer2->id,
            'store_id' => $store->id,
            'status' => 'pending',
            'shipping_address' => [
                'name' => 'Rina Wulandari',
                'phone' => '081512345678',
                'address' => 'Jl. Jenderal Soedirman No. 88, Purwokerto',
            ],
            'subtotal_amount' => 45000.00,
            'shipping_amount' => 12000.00,
            'discount_amount' => 0.00,
            'total_amount' => 57000.00,
            'courier' => 'JNE',
            'service_name' => 'REG',
            'placed_at' => now()->subMinutes(35),
        ]);

        OrderItem::create([
            'order_id' => $ord2->id,
            'product_id' => $p2->id,
            'variant_id' => $v2->id,
            'product_name_snapshot' => 'Matcha Latte',
            'variant_snapshot' => ['variant' => 'Dingin (Iced)'],
            'sku_snapshot' => 'KOP-MATC-01',
            'unit_price' => 15000.00,
            'quantity' => 3,
            'discount_amount' => 0.00,
            'line_total' => 45000.00,
        ]);

        // Order 3 (#ORD-0090)
        $ord3 = Order::create([
            'checkout_number' => 'CHK-' . strtoupper(Str::random(10)),
            'order_number' => '#ORD-0090',
            'buyer_user_id' => $customer3->id,
            'store_id' => $store->id,
            'status' => 'pending',
            'shipping_address' => [
                'name' => 'Dewi Lestari',
                'phone' => '081788990011',
                'address' => 'Perumahan Griya Satria Blok B3, Purwokerto',
            ],
            'subtotal_amount' => 100000.00,
            'shipping_amount' => 10000.00,
            'discount_amount' => 10000.00,
            'total_amount' => 100000.00,
            'courier' => 'SiCepat',
            'service_name' => 'BEST',
            'placed_at' => now()->subHour(),
        ]);

        OrderItem::create([
            'order_id' => $ord3->id,
            'product_id' => $p5->id,
            'variant_id' => $v5->id,
            'product_name_snapshot' => 'T-Shirt Cotton Combed',
            'variant_snapshot' => ['variant' => 'Hitam - XL'],
            'sku_snapshot' => 'FSH-TSHR-01',
            'unit_price' => 25000.00,
            'quantity' => 4,
            'discount_amount' => 0.00,
            'line_total' => 100000.00,
        ]);

        // 8. POS Shift & Sales
        $shift = PosShift::create([
            'store_id' => $store->id,
            'register_name' => 'Kasir Utama #1',
            'cashier_user_id' => $cashier->id,
            'opening_cash' => 200000.00,
            'status' => 'open',
            'opened_at' => now()->startOfDay()->addHours(8),
        ]);

        PosSale::create([
            'store_id' => $store->id,
            'shift_id' => $shift->id,
            'cashier_user_id' => $cashier->id,
            'customer_user_id' => $customer1->id,
            'receipt_number' => 'POS-20261022-0032',
            'items' => [
                ['name' => 'Americano', 'price' => 15000, 'qty' => 1, 'subtotal' => 15000],
                ['name' => 'Matcha Latte', 'price' => 15000, 'qty' => 2, 'subtotal' => 30000],
            ],
            'payments' => [
                ['method' => 'qris', 'amount' => 45000],
            ],
            'subtotal_amount' => 45000.00,
            'tax_amount' => 0.00,
            'total_amount' => 45000.00,
            'status' => 'completed',
            'sold_at' => now()->subMinutes(20),
        ]);

        // 9. Financial Entries
        FinancialEntry::create([
            'store_id' => $store->id,
            'entry_date' => now()->subHours(2),
            'direction' => 'income',
            'entry_type' => 'pos_sales',
            'category' => 'Penjualan Kasir POS',
            'amount' => 45000.00,
            'description' => 'Transaksi POS #POS-20261022-0032',
            'created_by' => $cashier->id,
        ]);

        FinancialEntry::create([
            'store_id' => $store->id,
            'entry_date' => now()->subDays(1),
            'direction' => 'expense',
            'entry_type' => 'inventory',
            'category' => 'Bahan Baku Kopi',
            'amount' => 250000.00,
            'description' => 'Pembelian Biji Kopi Arabika 2kg',
            'created_by' => $owner->id,
        ]);
    }
}
