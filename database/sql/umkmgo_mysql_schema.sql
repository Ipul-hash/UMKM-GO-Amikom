-- ==============================================================================
-- DATABASE SCHEMA & HIGH-PERFORMANCE INDEXES FOR UMKMgo
-- Platform: MySQL 8.0+ / MariaDB 10.5+
-- Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- Generated based on PRD: Final Project Pemrograman Mobile Lanjut - Amikom Purwokerto 2026
-- ==============================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

-- ------------------------------------------------------------------------------
-- DROP EXISTING TABLES (Reverse Dependency Order)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `financial_entries`;
DROP TABLE IF EXISTS `pos_sales`;
DROP TABLE IF EXISTS `pos_shifts`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `user_activity`;
DROP TABLE IF EXISTS `product_feedback`;
DROP TABLE IF EXISTS `stock_movements`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `promotions`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `favorites`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `store_members`;
DROP TABLE IF EXISTS `stores`;
DROP TABLE IF EXISTS `user_devices`;
DROP TABLE IF EXISTS `users`;

-- ------------------------------------------------------------------------------
-- 1. Tabel users (Data Pengguna)
-- ------------------------------------------------------------------------------
CREATE TABLE `users` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID',
    `full_name` VARCHAR(120) NOT NULL COMMENT 'Nama lengkap pengguna',
    `email` VARCHAR(255) NOT NULL COMMENT 'Email pengguna',
    `phone` VARCHAR(30) NULL DEFAULT NULL COMMENT 'Nomor telepon pengguna',
    `password_hash` TEXT NOT NULL COMMENT 'Password yang telah di-hash',
    `avatar_url` TEXT NULL DEFAULT NULL COMMENT 'URL foto profil',
    `role` VARCHAR(20) NOT NULL DEFAULT 'customer' COMMENT 'Peran pengguna: customer, seller_owner, admin',
    `status` VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'Status akun pengguna: active, suspended, inactive',
    `oauth_accounts` JSON NULL DEFAULT NULL COMMENT 'Data akun login OAuth',
    `addresses` JSON NULL DEFAULT NULL COMMENT 'Daftar alamat pengguna',
    `notification_prefs` JSON NULL DEFAULT NULL COMMENT 'Preferensi notifikasi',
    `reset_token_hash` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Hash token reset password',
    `reset_token_expires_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu kedaluwarsa token reset',
    `email_verified_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu verifikasi email',
    `phone_verified_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu verifikasi telepon',
    `last_login_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu login terakhir',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu akun dibuat',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu data terakhir diperbarui',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_users_email` (`email`),
    KEY `idx_users_phone` (`phone`),
    KEY `idx_users_role_status` (`role`, `status`),
    KEY `idx_users_reset_token` (`reset_token_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Data Pengguna Sistem UMKMgo';

-- ------------------------------------------------------------------------------
-- 2. Tabel user_devices (Perangkat Pengguna / Sesi Multi-Platform)
-- ------------------------------------------------------------------------------
CREATE TABLE `user_devices` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID perangkat',
    `user_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke users.id',
    `refresh_token_hash` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Hash token sesi login',
    `push_token` VARCHAR(512) NULL DEFAULT NULL COMMENT 'Token notifikasi perangkat (FCM/APNS)',
    `platform` VARCHAR(20) NOT NULL COMMENT 'Platform perangkat: android, ios, web, desktop',
    `device_name` VARCHAR(120) NULL DEFAULT NULL COMMENT 'Nama perangkat',
    `expires_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu kedaluwarsa sesi',
    `revoked_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu pencabutan sesi',
    `last_seen_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu perangkat terakhir aktif',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu perangkat terdaftar',
    PRIMARY KEY (`id`),
    KEY `idx_user_devices_user_revoked` (`user_id`, `revoked_at`),
    KEY `idx_user_devices_refresh_token` (`refresh_token_hash`),
    KEY `idx_user_devices_platform` (`platform`),
    KEY `idx_user_devices_push_token` (`push_token`(255)),
    CONSTRAINT `fk_user_devices_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Perangkat Pengguna dan Sesi Login';

-- ------------------------------------------------------------------------------
-- 3. Tabel stores (Data Toko UMKM)
-- ------------------------------------------------------------------------------
CREATE TABLE `stores` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID toko',
    `owner_user_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke users.id sebagai pemilik',
    `name` VARCHAR(150) NOT NULL COMMENT 'Nama toko',
    `slug` VARCHAR(180) NOT NULL COMMENT 'Identitas URL toko unik',
    `description` TEXT NULL DEFAULT NULL COMMENT 'Deskripsi toko',
    `logo_url` TEXT NULL DEFAULT NULL COMMENT 'URL logo toko',
    `banner_url` TEXT NULL DEFAULT NULL COMMENT 'URL banner toko',
    `phone` VARCHAR(30) NULL DEFAULT NULL COMMENT 'Nomor telepon toko',
    `email` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Email toko',
    `business_type` VARCHAR(80) NULL DEFAULT NULL COMMENT 'Jenis usaha: kuliner, fashion, kerajinan, dll.',
    `status` VARCHAR(24) NOT NULL DEFAULT 'pending' COMMENT 'Status: pending, active, suspended, closed',
    `address_line` TEXT NULL DEFAULT NULL COMMENT 'Alamat lengkap toko',
    `province` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Provinsi toko',
    `city` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Kota atau kabupaten toko',
    `district` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Kecamatan toko',
    `postal_code` VARCHAR(12) NULL DEFAULT NULL COMMENT 'Kode pos',
    `latitude` DECIMAL(10,7) NULL DEFAULT NULL COMMENT 'Garis lintang lokasi toko',
    `longitude` DECIMAL(10,7) NULL DEFAULT NULL COMMENT 'Garis bujur lokasi toko',
    `opening_hours` JSON NULL DEFAULT NULL COMMENT 'Jadwal operasional toko',
    `verification_documents` JSON NULL DEFAULT NULL COMMENT 'Dokumen verifikasi usaha',
    `verified_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu toko diverifikasi',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu toko dibuat',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu data toko diperbarui',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_stores_slug` (`slug`),
    KEY `idx_stores_owner` (`owner_user_id`),
    KEY `idx_stores_status` (`status`),
    KEY `idx_stores_geo` (`latitude`, `longitude`),
    KEY `idx_stores_location` (`city`, `district`),
    FULLTEXT KEY `idx_stores_name_fulltext` (`name`),
    CONSTRAINT `fk_stores_owner` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Data Toko UMKM';

-- ------------------------------------------------------------------------------
-- 4. Tabel store_members (Anggota & Karyawan Toko)
-- ------------------------------------------------------------------------------
CREATE TABLE `store_members` (
    `store_id` CHAR(36) NOT NULL COMMENT 'Primary key & FK ke stores.id',
    `user_id` CHAR(36) NOT NULL COMMENT 'Primary key & FK ke users.id',
    `role` VARCHAR(20) NOT NULL COMMENT 'Peran anggota: seller_owner, seller_staff, cashier',
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Status keaktifan anggota',
    `joined_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu bergabung dengan toko',
    PRIMARY KEY (`store_id`, `user_id`),
    KEY `idx_store_members_user` (`user_id`),
    KEY `idx_store_members_store_role` (`store_id`, `role`, `is_active`),
    CONSTRAINT `fk_store_members_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_store_members_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Anggota dan Staf Toko UMKM';

-- ------------------------------------------------------------------------------
-- 5. Tabel categories (Kategori Produk Berhirarki)
-- ------------------------------------------------------------------------------
CREATE TABLE `categories` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID kategori',
    `parent_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke categories.id untuk subkategori',
    `name` VARCHAR(100) NOT NULL COMMENT 'Nama kategori',
    `slug` VARCHAR(120) NOT NULL COMMENT 'Identitas URL kategori unik',
    `description` TEXT NULL DEFAULT NULL COMMENT 'Deskripsi kategori',
    `icon_url` TEXT NULL DEFAULT NULL COMMENT 'URL ikon kategori',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT 'Urutan tampilan kategori',
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Status aktif kategori',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu kategori dibuat',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_categories_slug` (`slug`),
    KEY `idx_categories_parent_active` (`parent_id`, `is_active`, `sort_order`),
    KEY `idx_categories_active_sort` (`is_active`, `sort_order`),
    CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Kategori Produk Bertingkat';

-- ------------------------------------------------------------------------------
-- 6. Tabel products (Data Produk UMKM)
-- ------------------------------------------------------------------------------
CREATE TABLE `products` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID produk',
    `store_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke stores.id',
    `category_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke categories.id',
    `name` VARCHAR(180) NOT NULL COMMENT 'Nama produk',
    `slug` VARCHAR(220) NOT NULL COMMENT 'Identitas URL produk unik',
    `description` TEXT NULL DEFAULT NULL COMMENT 'Deskripsi produk',
    `condition` VARCHAR(20) NOT NULL DEFAULT 'baru' COMMENT 'Kondisi produk: baru atau bekas',
    `status` VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'Status publikasi: draft, active, archived',
    `weight_gram` INT NOT NULL DEFAULT 0 COMMENT 'Berat produk dalam gram untuk hitung ongkir',
    `images` JSON NULL DEFAULT NULL COMMENT 'Daftar gambar produk (array URL)',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu produk dibuat',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu data produk diperbarui',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_products_slug` (`slug`),
    KEY `idx_products_store_status` (`store_id`, `status`),
    KEY `idx_products_cat_status` (`category_id`, `status`),
    KEY `idx_products_status_created` (`status`, `created_at`),
    FULLTEXT KEY `idx_products_search` (`name`, `description`),
    CONSTRAINT `fk_products_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Data Katalog Produk Toko UMKM';

-- ------------------------------------------------------------------------------
-- 7. Tabel product_variants (Varian & Stok Produk)
-- ------------------------------------------------------------------------------
CREATE TABLE `product_variants` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID varian',
    `product_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke products.id',
    `sku` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Kode SKU unik / barcode stok produk',
    `variant_name` VARCHAR(120) NOT NULL COMMENT 'Nama varian produk (e.g. Merah - XL, Standar)',
    `attributes` JSON NULL DEFAULT NULL COMMENT 'Atribut varian seperti ukuran, warna, rasa',
    `price` DECIMAL(14,2) NOT NULL COMMENT 'Harga jual varian',
    `compare_at_price` DECIMAL(14,2) NULL DEFAULT NULL COMMENT 'Harga pembanding sebelum diskon (coret)',
    `stock_quantity` INT NOT NULL DEFAULT 0 COMMENT 'Jumlah stok tersedia',
    `low_stock_threshold` INT NOT NULL DEFAULT 5 COMMENT 'Batas minimum stok untuk peringatan',
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Status aktif varian',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu varian dibuat',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu varian diperbarui',
    PRIMARY KEY (`id`),
    KEY `idx_variants_product_active` (`product_id`, `is_active`),
    KEY `idx_variants_sku` (`sku`),
    KEY `idx_variants_price` (`price`),
    KEY `idx_variants_low_stock` (`stock_quantity`, `low_stock_threshold`),
    CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Varian dan Persediaan Stok Produk';

-- ------------------------------------------------------------------------------
-- 8. Tabel favorites (Favorit Produk & Toko)
-- ------------------------------------------------------------------------------
CREATE TABLE `favorites` (
    `user_id` CHAR(36) NOT NULL COMMENT 'Primary key gabungan dan FK ke users.id',
    `target_type` VARCHAR(10) NOT NULL COMMENT 'Jenis favorit: product atau store',
    `target_id` CHAR(36) NOT NULL COMMENT 'ID produk atau toko yang difavoritkan',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu ditambahkan ke favorit',
    PRIMARY KEY (`user_id`, `target_type`, `target_id`),
    KEY `idx_favorites_target` (`target_type`, `target_id`),
    CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Daftar Favorit Pengguna';

-- ------------------------------------------------------------------------------
-- 9. Tabel cart_items (Keranjang Belanja Pengguna)
-- ------------------------------------------------------------------------------
CREATE TABLE `cart_items` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID item keranjang',
    `user_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke users.id',
    `variant_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke product_variants.id',
    `quantity` INT NOT NULL DEFAULT 1 COMMENT 'Jumlah produk dalam keranjang',
    `added_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu produk ditambahkan',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu item diperbarui',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_cart_user_variant` (`user_id`, `variant_id`),
    KEY `idx_cart_variant` (`variant_id`),
    CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_cart_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Keranjang Belanja Pengguna';

-- ------------------------------------------------------------------------------
-- 10. Tabel promotions (Promosi dan Voucher Diskon)
-- ------------------------------------------------------------------------------
CREATE TABLE `promotions` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID promosi',
    `store_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke stores.id, NULL jika promosi global platform',
    `name` VARCHAR(150) NOT NULL COMMENT 'Nama promosi',
    `description` TEXT NULL DEFAULT NULL COMMENT 'Deskripsi promosi',
    `code` VARCHAR(60) NOT NULL COMMENT 'Kode voucher unik',
    `discount_type` VARCHAR(20) NOT NULL COMMENT 'Jenis diskon: percentage atau fixed',
    `discount_value` DECIMAL(14,2) NOT NULL COMMENT 'Nilai diskon (persen atau nominal)',
    `max_discount_amount` DECIMAL(14,2) NULL DEFAULT NULL COMMENT 'Batas maksimal potongan diskon',
    `minimum_purchase` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Minimal nilai transaksi pembelian',
    `product_ids` JSON NULL DEFAULT NULL COMMENT 'Daftar ID produk sasaran (array)',
    `usage_limit` INT NULL DEFAULT NULL COMMENT 'Batas total penggunaan voucher',
    `per_user_limit` INT NOT NULL DEFAULT 1 COMMENT 'Batas penggunaan per pengguna',
    `used_count` INT NOT NULL DEFAULT 0 COMMENT 'Jumlah penggunaan promosi saat ini',
    `starts_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu mulai promosi',
    `ends_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu berakhir promosi',
    `status` VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'Status: active, expired, disabled',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu promosi dibuat',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_promotions_code` (`code`),
    KEY `idx_promotions_store_status` (`store_id`, `status`),
    KEY `idx_promotions_validity` (`status`, `starts_at`, `ends_at`),
    CONSTRAINT `fk_promotions_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Voucher Diskon dan Promosi';

-- ------------------------------------------------------------------------------
-- 11. Tabel orders (Pesanan / Transaksi Marketplace)
-- ------------------------------------------------------------------------------
CREATE TABLE `orders` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID pesanan',
    `checkout_number` VARCHAR(40) NOT NULL COMMENT 'Nomor checkout bersama untuk beberapa toko',
    `order_number` VARCHAR(50) NOT NULL COMMENT 'Nomor pesanan unik toko',
    `buyer_user_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke users.id sebagai pembeli',
    `store_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke stores.id',
    `status` VARCHAR(24) NOT NULL DEFAULT 'pending' COMMENT 'Status: pending, paid, processing, shipped, delivered, cancelled, refunded',
    `shipping_address` JSON NOT NULL COMMENT 'Snapshot alamat pengiriman saat checkout',
    `subtotal_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Total harga produk sebelum ongkir & diskon',
    `shipping_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Biaya pengiriman kurir',
    `discount_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Total potongan harga voucher/promo',
    `service_fee` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Biaya penanganan / layanan',
    `total_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Total tagihan akhir pesanan',
    `buyer_note` TEXT NULL DEFAULT NULL COMMENT 'Catatan dari pembeli',
    `applied_promotions` JSON NULL DEFAULT NULL COMMENT 'Daftar promosi yang digunakan',
    `courier` VARCHAR(80) NULL DEFAULT NULL COMMENT 'Nama ekspedisi/kurir (JNE, J&T, SiCepat, dll)',
    `service_name` VARCHAR(80) NULL DEFAULT NULL COMMENT 'Jenis paket layanan ekspedisi',
    `tracking_number` VARCHAR(120) NULL DEFAULT NULL COMMENT 'Nomor resi pelacakan pengiriman',
    `shipment_status` VARCHAR(24) NULL DEFAULT NULL COMMENT 'Status pengiriman kurir',
    `shipped_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu pesanan diserahkan ke kurir',
    `delivered_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu pesanan tiba/diterima pembeli',
    `tracking_events` JSON NULL DEFAULT NULL COMMENT 'Riwayat checkpoint pelacakan pengiriman',
    `status_history` JSON NULL DEFAULT NULL COMMENT 'Riwayat perubahan status pesanan',
    `placed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pesanan dibuat',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu pesanan diperbarui',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_orders_order_number` (`order_number`),
    KEY `idx_orders_buyer_status` (`buyer_user_id`, `status`, `placed_at`),
    KEY `idx_orders_store_status` (`store_id`, `status`, `placed_at`),
    KEY `idx_orders_checkout` (`checkout_number`),
    KEY `idx_orders_tracking` (`tracking_number`),
    CONSTRAINT `fk_orders_buyer` FOREIGN KEY (`buyer_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_orders_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Data Pesanan Transaksi Konsumen';

-- ------------------------------------------------------------------------------
-- 12. Tabel order_items (Detail Item dalam Pesanan)
-- ------------------------------------------------------------------------------
CREATE TABLE `order_items` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID detail item',
    `order_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke orders.id',
    `product_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke products.id (nullable jika produk dihapus)',
    `variant_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke product_variants.id (nullable)',
    `product_name_snapshot` VARCHAR(180) NOT NULL COMMENT 'Nama produk saat transaksi berlangsung',
    `variant_snapshot` JSON NULL DEFAULT NULL COMMENT 'Detail atribut varian saat transaksi',
    `sku_snapshot` VARCHAR(100) NULL DEFAULT NULL COMMENT 'SKU produk saat transaksi',
    `unit_price` DECIMAL(14,2) NOT NULL COMMENT 'Harga satuan saat transaksi',
    `quantity` INT NOT NULL COMMENT 'Jumlah produk yang dibeli',
    `discount_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Diskon per item pesanan',
    `line_total` DECIMAL(14,2) NOT NULL COMMENT 'Total harga item setelah diskon',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu detail pesanan dibuat',
    PRIMARY KEY (`id`),
    KEY `idx_order_items_order` (`order_id`),
    KEY `idx_order_items_product` (`product_id`),
    KEY `idx_order_items_variant` (`variant_id`),
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Detail Item Produk Pesanan';

-- ------------------------------------------------------------------------------
-- 13. Tabel payments (Pembayaran dan Pengembalian Dana / Refund)
-- ------------------------------------------------------------------------------
CREATE TABLE `payments` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID transaksi pembayaran',
    `payment_number` VARCHAR(50) NOT NULL COMMENT 'Nomor pembayaran unik',
    `kind` VARCHAR(10) NOT NULL DEFAULT 'payment' COMMENT 'Jenis transaksi: payment atau refund',
    `checkout_number` VARCHAR(40) NULL DEFAULT NULL COMMENT 'Nomor checkout terkait',
    `order_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke orders.id (dapat NULL jika pembayaran gabungan)',
    `parent_payment_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke payments.id untuk pembayaran induk',
    `method` VARCHAR(30) NOT NULL COMMENT 'Metode pembayaran: qris, bank_transfer, ewallet, cod, cash',
    `provider` VARCHAR(80) NULL DEFAULT NULL COMMENT 'Penyedia layanan (Midtrans, Xendit, Manual, dll)',
    `provider_reference` VARCHAR(180) NULL DEFAULT NULL COMMENT 'ID/Referensi transaksi dari penyedia gateway',
    `amount` DECIMAL(14,2) NOT NULL COMMENT 'Nominal pembayaran atau refund',
    `status` VARCHAR(24) NOT NULL DEFAULT 'pending' COMMENT 'Status: pending, paid, failed, expired, refunded',
    `reason` TEXT NULL DEFAULT NULL COMMENT 'Alasan pengembalian dana',
    `requested_by` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke users.id pemohon refund',
    `reviewed_by` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke users.id peninjau refund',
    `reviewed_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu peninjauan refund',
    `paid_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu pembayaran berhasil dikonfirmasi',
    `expires_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu kedaluwarsa batas pembayaran',
    `provider_payload` JSON NULL DEFAULT NULL COMMENT 'Data respons webhook lengkap gateway',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu transaksi dibuat',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu transaksi diperbarui',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_payments_number` (`payment_number`),
    KEY `idx_payments_order` (`order_id`, `status`),
    KEY `idx_payments_checkout` (`checkout_number`),
    KEY `idx_payments_provider_ref` (`provider_reference`),
    KEY `idx_payments_status_expires` (`status`, `expires_at`),
    CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_payments_parent` FOREIGN KEY (`parent_payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_payments_requester` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_payments_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Data Pembayaran dan Refund';

-- ------------------------------------------------------------------------------
-- 14. Tabel stock_movements (Audit Riwayat Pergerakan Stok)
-- ------------------------------------------------------------------------------
CREATE TABLE `stock_movements` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID mutasi stok',
    `store_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke stores.id',
    `variant_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke product_variants.id',
    `movement_type` VARCHAR(24) NOT NULL COMMENT 'Jenis pergerakan: in, out, adjustment, order_sale, pos_sale, return',
    `quantity_delta` INT NOT NULL COMMENT 'Perubahan kuantitas (+ atau -)',
    `quantity_after` INT NOT NULL COMMENT 'Jumlah stok akhir setelah perubahan',
    `reference_type` VARCHAR(40) NULL DEFAULT NULL COMMENT 'Tipe referensi: order, pos_sale, adjustment',
    `reference_id` CHAR(36) NULL DEFAULT NULL COMMENT 'ID transaksi referensi terkait',
    `adjustment_number` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Nomor dokumen penyesuaian manual',
    `note` TEXT NULL DEFAULT NULL COMMENT 'Catatan keterangan perubahan stok',
    `created_by` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke users.id yang mengubah stok',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu perubahan stok tercatat',
    PRIMARY KEY (`id`),
    KEY `idx_stock_mov_store_created` (`store_id`, `created_at`),
    KEY `idx_stock_mov_variant_created` (`variant_id`, `created_at`),
    KEY `idx_stock_mov_ref` (`reference_type`, `reference_id`),
    KEY `idx_stock_mov_adjustment` (`adjustment_number`),
    CONSTRAINT `fk_stock_mov_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_stock_mov_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_stock_mov_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Riwayat Pergerakan dan Audit Stok';

-- ------------------------------------------------------------------------------
-- 15. Tabel product_feedback (Ulasan, Pertanyaan, dan Laporan Produk)
-- ------------------------------------------------------------------------------
CREATE TABLE `product_feedback` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID feedback',
    `product_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke products.id',
    `user_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke users.id pemberi feedback',
    `type` VARCHAR(10) NOT NULL DEFAULT 'review' COMMENT 'Jenis feedback: review, question, report',
    `order_item_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke order_items.id jika ulasan pembelian',
    `rating` SMALLINT NULL DEFAULT NULL COMMENT 'Nilai rating produk 1-5',
    `reason` VARCHAR(80) NULL DEFAULT NULL COMMENT 'Alasan jika bertipe report',
    `content` TEXT NULL DEFAULT NULL COMMENT 'Isi teks ulasan, pertanyaan, atau laporan',
    `reply` TEXT NULL DEFAULT NULL COMMENT 'Jawaban atau tanggapan dari penjual/admin',
    `replied_by` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke users.id yang membalas',
    `replied_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu balasan diberikan',
    `status` VARCHAR(20) NOT NULL DEFAULT 'published' COMMENT 'Status: published, hidden, flagged',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu feedback dibuat',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu feedback diperbarui',
    PRIMARY KEY (`id`),
    KEY `idx_feedback_product_type_status` (`product_id`, `type`, `status`),
    KEY `idx_feedback_user` (`user_id`),
    KEY `idx_feedback_order_item` (`order_item_id`),
    CONSTRAINT `fk_feedback_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_feedback_order_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_feedback_replier` FOREIGN KEY (`replied_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Ulasan, Tanya-Jawab, dan Laporan Produk';

-- ------------------------------------------------------------------------------
-- 16. Tabel user_activity (Log Aktivitas Pengguna & Rekomendasi)
-- ------------------------------------------------------------------------------
CREATE TABLE `user_activity` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID aktivitas',
    `user_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke users.id (nullable untuk guest)',
    `type` VARCHAR(10) NOT NULL COMMENT 'Jenis aktivitas: search atau view',
    `search_query` VARCHAR(250) NULL DEFAULT NULL COMMENT 'Kata kunci pencarian yang dimasukkan',
    `product_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke products.id yang dilihat',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu aktivitas dilakukan',
    PRIMARY KEY (`id`),
    KEY `idx_activity_user_created` (`user_id`, `created_at`),
    KEY `idx_activity_type_created` (`type`, `created_at`),
    KEY `idx_activity_product` (`product_id`),
    KEY `idx_activity_search_query` (`search_query`(100)),
    CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_activity_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catatan Aktivitas Pencarian dan Kunjungan Pengguna';

-- ------------------------------------------------------------------------------
-- 17. Tabel notifications (Pemberitahuan Sistem & Transaksi)
-- ------------------------------------------------------------------------------
CREATE TABLE `notifications` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID notifikasi',
    `user_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke users.id',
    `type` VARCHAR(40) NOT NULL COMMENT 'Jenis notifikasi (order_status, promo, chat, dll)',
    `title` VARCHAR(180) NOT NULL COMMENT 'Judul pesan notifikasi',
    `body` TEXT NOT NULL COMMENT 'Isi pesan pemberitahuan',
    `deep_link` TEXT NULL DEFAULT NULL COMMENT 'Tautan routing dalam aplikasi',
    `payload` JSON NULL DEFAULT NULL COMMENT 'Data muatan JSON tambahan',
    `read_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu notifikasi dibaca',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu notifikasi dikirimkan',
    PRIMARY KEY (`id`),
    KEY `idx_notifications_user_read` (`user_id`, `read_at`, `created_at`),
    CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notifikasi Pengguna';

-- ------------------------------------------------------------------------------
-- 18. Tabel pos_shifts (Sesi Shift Kasir Desktop POS)
-- ------------------------------------------------------------------------------
CREATE TABLE `pos_shifts` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID shift',
    `store_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke stores.id',
    `register_name` VARCHAR(100) NOT NULL COMMENT 'Nama mesin/loket kasir',
    `cashier_user_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke users.id sebagai kasir bertugas',
    `opening_cash` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Modal kas awal saat buka shift',
    `closing_cash` DECIMAL(14,2) NULL DEFAULT NULL COMMENT 'Uang kas fisik saat tutup shift',
    `expected_cash` DECIMAL(14,2) NULL DEFAULT NULL COMMENT 'Uang kas kalkulasi sistem',
    `status` VARCHAR(20) NOT NULL DEFAULT 'open' COMMENT 'Status shift: open, closed, cancelled',
    `note` TEXT NULL DEFAULT NULL COMMENT 'Catatan keterangan kasir',
    `opened_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu shift dibuka',
    `closed_at` DATETIME NULL DEFAULT NULL COMMENT 'Waktu shift ditutup',
    PRIMARY KEY (`id`),
    KEY `idx_pos_shifts_store_status` (`store_id`, `status`),
    KEY `idx_pos_shifts_cashier_status` (`cashier_user_id`, `status`),
    KEY `idx_pos_shifts_opened` (`opened_at`),
    CONSTRAINT `fk_pos_shifts_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pos_shifts_cashier` FOREIGN KEY (`cashier_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sesi Operasional Shift Kasir POS';

-- ------------------------------------------------------------------------------
-- 19. Tabel pos_sales (Transaksi Penjualan Kasir POS)
-- ------------------------------------------------------------------------------
CREATE TABLE `pos_sales` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID transaksi penjualan POS',
    `store_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke stores.id',
    `shift_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke pos_shifts.id',
    `cashier_user_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke users.id sebagai kasir pelaksana',
    `customer_user_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke users.id jika pelanggan terdaftar',
    `receipt_number` VARCHAR(60) NOT NULL COMMENT 'Nomor struk kasir unik',
    `items` JSON NOT NULL COMMENT 'Daftar item produk yang dibeli beserta harga & varian',
    `payments` JSON NOT NULL COMMENT 'Rincian metode pembayaran (tunai, qris, kartu, dll)',
    `subtotal_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Total belanja sebelum diskon & pajak',
    `discount_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Total diskon transaksi kasir',
    `tax_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Total pajak penjualan',
    `total_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 COMMENT 'Total tagihan pembayaran kasir',
    `status` VARCHAR(20) NOT NULL DEFAULT 'completed' COMMENT 'Status: completed, cancelled, refunded',
    `note` TEXT NULL DEFAULT NULL COMMENT 'Catatan transaksi kasir',
    `sold_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu transaksi penjualan kasir',
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_pos_sales_receipt` (`receipt_number`),
    KEY `idx_pos_sales_store_sold` (`store_id`, `sold_at`),
    KEY `idx_pos_sales_shift` (`shift_id`),
    KEY `idx_pos_sales_cashier` (`cashier_user_id`),
    KEY `idx_pos_sales_customer` (`customer_user_id`),
    CONSTRAINT `fk_pos_sales_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_pos_sales_shift` FOREIGN KEY (`shift_id`) REFERENCES `pos_shifts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_pos_sales_cashier` FOREIGN KEY (`cashier_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_pos_sales_customer` FOREIGN KEY (`customer_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Data Transaksi Penjualan Kasir Desktop POS';

-- ------------------------------------------------------------------------------
-- 20. Tabel financial_entries (Pencatatan Keuangan dan Arus Kas Toko)
-- ------------------------------------------------------------------------------
CREATE TABLE `financial_entries` (
    `id` CHAR(36) NOT NULL COMMENT 'Primary key UUID catatan keuangan',
    `store_id` CHAR(36) NOT NULL COMMENT 'Foreign key ke stores.id',
    `entry_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Tanggal pemasukan atau pengeluaran',
    `direction` VARCHAR(10) NOT NULL COMMENT 'Jenis arus: income atau expense',
    `entry_type` VARCHAR(40) NOT NULL COMMENT 'Jenis transaksi: sales, operational, inventory, utility, dll',
    `category` VARCHAR(100) NOT NULL COMMENT 'Kategori transaksi keuangan',
    `amount` DECIMAL(14,2) NOT NULL COMMENT 'Nominal pemasukan atau pengeluaran',
    `description` TEXT NULL DEFAULT NULL COMMENT 'Keterangan detail transaksi',
    `attachment_url` TEXT NULL DEFAULT NULL COMMENT 'URL bukti transaksi atau nota',
    `source_table` VARCHAR(60) NULL DEFAULT NULL COMMENT 'Nama tabel asal (pos_sales, orders, dll)',
    `source_id` CHAR(36) NULL DEFAULT NULL COMMENT 'ID record sumber transaksi',
    `created_by` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke users.id pencatat',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu catatan keuangan dibuat',
    PRIMARY KEY (`id`),
    KEY `idx_fin_store_date` (`store_id`, `entry_date`),
    KEY `idx_fin_store_dir_date` (`store_id`, `direction`, `entry_date`),
    KEY `idx_fin_source` (`source_table`, `source_id`),
    KEY `idx_fin_created_by` (`created_by`),
    CONSTRAINT `fk_financial_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_financial_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Buku Kas dan Pencatatan Keuangan Toko UMKM';

-- ------------------------------------------------------------------------------
-- 21. Tabel audit_logs (Log Jejak Audit Aktivitas Sistem)
-- ------------------------------------------------------------------------------
CREATE TABLE `audit_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key log auto-increment',
    `actor_user_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke users.id sebagai pelaku tindakan',
    `store_id` CHAR(36) NULL DEFAULT NULL COMMENT 'Foreign key ke stores.id terkait',
    `action` VARCHAR(80) NOT NULL COMMENT 'Jenis tindakan (create, update, delete, login, adjust_stock)',
    `entity_type` VARCHAR(80) NOT NULL COMMENT 'Jenis entitas data yang diubah (product, order, store, dll)',
    `entity_id` CHAR(36) NULL DEFAULT NULL COMMENT 'ID objek entitas yang terkait',
    `metadata` JSON NULL DEFAULT NULL COMMENT 'Detail sebelum dan sesudah perubahan data',
    `ip_address` VARCHAR(45) NULL DEFAULT NULL COMMENT 'Alamat IP pengguna pelaku',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu aktivitas dicatat',
    PRIMARY KEY (`id`),
    KEY `idx_audit_actor_created` (`actor_user_id`, `created_at`),
    KEY `idx_audit_store_created` (`store_id`, `created_at`),
    KEY `idx_audit_entity` (`entity_type`, `entity_id`),
    KEY `idx_audit_action` (`action`),
    CONSTRAINT `fk_audit_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_audit_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catatan Jejak Audit dan Keamanan Sistem';

SET FOREIGN_KEY_CHECKS = 1;
