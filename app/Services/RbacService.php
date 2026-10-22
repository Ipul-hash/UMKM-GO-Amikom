<?php

namespace App\Services;

use App\Models\Store;
use App\Models\StoreMember;
use App\Models\User;

class RbacService
{
    public const FLOW_PRODUK = 'Flow 1: Manajemen Produk & Stok';
    public const FLOW_PESANAN = 'Flow 2: Operasional Pesanan & Pengiriman';
    public const FLOW_LAPORAN = 'Flow 3: Penjualan & Laporan Toko';

    public static function permissionGroups(): array
    {
        return [
            self::FLOW_PRODUK => [
                'description' => 'Tambah atau edit produk, atur harga, foto, kategori, stok, validasi data, simpan ke database.',
                'permissions' => [
                    'products.view' => [
                        'label' => 'Lihat Produk',
                        'description' => 'Melihat daftar produk dan detail katalog toko',
                    ],
                    'products.manage' => [
                        'label' => 'Kelola Produk',
                        'description' => 'Menambah, mengedit, atur harga, foto, kategori, dan stok',
                    ],
                    'products.delete' => [
                        'label' => 'Hapus Produk',
                        'description' => 'Menghapus data produk dari katalog',
                    ],
                    'inventory.view' => [
                        'label' => 'Lihat Inventaris',
                        'description' => 'Melihat status stok barang dan mutasi pergudangan',
                    ],
                    'inventory.manage' => [
                        'label' => 'Penyesuaian Stok',
                        'description' => 'Melakukan stock opname dan koreksi stok manual',
                    ],
                ],
            ],
            self::FLOW_PESANAN => [
                'description' => 'Lihat pesanan masuk, periksa pembayaran, proses pesanan, siapkan barang, atur pengiriman, update status, kasir POS.',
                'permissions' => [
                    'pos.access' => [
                        'label' => 'Akses POS / Kasir',
                        'description' => 'Melakukan penjualan offline di kasir dan mencetak struk',
                    ],
                    'orders.view' => [
                        'label' => 'Lihat Pesanan Masuk',
                        'description' => 'Melihat pesanan masuk dan memeriksa pembayaran pelanggan',
                    ],
                    'orders.process' => [
                        'label' => 'Proses & Siapkan Barang',
                        'description' => 'Menerima pesanan dan menyiapkan pesanan masuk',
                    ],
                    'orders.ship' => [
                        'label' => 'Atur Pengiriman',
                        'description' => 'Memilih kurir, memasukkan nomor resi, dan perbarui status pesanan',
                    ],
                    'customers.view' => [
                        'label' => 'Lihat Pelanggan',
                        'description' => 'Melihat profil dan riwayat belanja pelanggan',
                    ],
                    'customers.manage' => [
                        'label' => 'Kelola Pelanggan',
                        'description' => 'Menambah dan memperbarui data pelanggan',
                    ],
                    'promo.manage' => [
                        'label' => 'Kelola Promo & Diskon',
                        'description' => 'Membuat kupon diskon dan mengatur promosi toko',
                    ],
                    'suppliers.manage' => [
                        'label' => 'Kelola Supplier',
                        'description' => 'Melihat dan mengelola daftar mitra supplier pemasok',
                    ],
                ],
            ],
            self::FLOW_LAPORAN => [
                'description' => 'Lihat penjualan dan stok, laporan toko, keuangan, manajemen karyawan, dan pengaturan toko.',
                'permissions' => [
                    'reports.view' => [
                        'label' => 'Laporan Penjualan',
                        'description' => 'Melihat statistik omzet, performa produk, dan unduh laporan',
                    ],
                    'finance.view' => [
                        'label' => 'Lihat Keuangan',
                        'description' => 'Melihat buku kas, catatan pemasukan, dan pengeluaran',
                    ],
                    'finance.manage' => [
                        'label' => 'Kelola Keuangan',
                        'description' => 'Mencatat transaksi kas masuk dan pengeluaran operasional',
                    ],
                    'employees.manage' => [
                        'label' => 'Kelola Karyawan & RBAC',
                        'description' => 'Menambah staf dan mengatur Permission Matrix hak akses',
                    ],
                    'settings.manage' => [
                        'label' => 'Pengaturan Toko',
                        'description' => 'Mengubah profil toko, logo, banner, dan konfigurasi toko',
                    ],
                ],
            ],
        ];
    }

    public static function allPermissionKeys(): array
    {
        $keys = [];
        foreach (self::permissionGroups() as $group) {
            foreach (array_keys($group['permissions']) as $key) {
                $keys[] = $key;
            }
        }
        return $keys;
    }

    public static function defaultPresets(): array
    {
        return [
            'seller_owner' => [
                'label' => 'Owner / Pemilik',
                'description' => 'Akses penuh tanpa batas ke semua fitur sistem',
                'permissions' => ['*'],
            ],
            'manager' => [
                'label' => 'Manajer Toko',
                'description' => 'Supervisi operasional, katalog produk, pesanan, dan laporan toko',
                'permissions' => [
                    'products.view',
                    'products.manage',
                    'inventory.view',
                    'inventory.manage',
                    'pos.access',
                    'orders.view',
                    'orders.process',
                    'orders.ship',
                    'customers.view',
                    'customers.manage',
                    'promo.manage',
                    'suppliers.manage',
                    'reports.view',
                    'finance.view',
                ],
            ],
            'cashier' => [
                'label' => 'Kasir Utama',
                'description' => 'Fokus transaksi POS kasir offline, lihat pesanan masuk, dan diskon',
                'permissions' => [
                    'pos.access',
                    'orders.view',
                    'customers.view',
                    'customers.manage',
                    'promo.manage',
                ],
            ],
            'seller_staff' => [
                'label' => 'Staf Operasional / Gudang',
                'description' => 'Fokus katalog produk, cek inventaris, dan pengiriman barang',
                'permissions' => [
                    'products.view',
                    'products.manage',
                    'inventory.view',
                    'inventory.manage',
                    'orders.view',
                    'orders.process',
                    'orders.ship',
                    'suppliers.manage',
                ],
            ],
        ];
    }

    public static function resolveStoreId(?string $storeId = null): ?string
    {
        return $storeId ?: Store::value('id');
    }

    public static function getUserMembership(User $user, ?string $storeId = null): ?StoreMember
    {
        $storeId = self::resolveStoreId($storeId);
        if (!$storeId) {
            return null;
        }

        return StoreMember::where('store_id', $storeId)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();
    }

    public static function isStoreOwner(User $user, ?string $storeId = null): bool
    {
        if ($user->role === 'seller_owner' || $user->role === 'admin') {
            return true;
        }

        $storeId = self::resolveStoreId($storeId);
        if (!$storeId) {
            return false;
        }

        $isOwnerByStore = Store::where('id', $storeId)->where('owner_user_id', $user->id)->exists();
        if ($isOwnerByStore) {
            return true;
        }

        $membership = StoreMember::where('store_id', $storeId)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        return $membership && $membership->role === 'seller_owner';
    }

    public static function getPermissions(User $user, ?string $storeId = null): array
    {
        if (self::isStoreOwner($user, $storeId)) {
            return ['*'];
        }

        $membership = self::getUserMembership($user, $storeId);
        if (!$membership) {
            return [];
        }

        if (is_array($membership->permissions) && count($membership->permissions) > 0) {
            return $membership->permissions;
        }

        $presets = self::defaultPresets();
        return $presets[$membership->role]['permissions'] ?? [];
    }

    public static function hasPermission(User $user, string $permission, ?string $storeId = null): bool
    {
        if (self::isStoreOwner($user, $storeId)) {
            return true;
        }

        $permissions = self::getPermissions($user, $storeId);
        if (in_array('*', $permissions, true)) {
            return true;
        }

        return in_array($permission, $permissions, true);
    }

    public static function hasAnyPermission(User $user, array $permissions, ?string $storeId = null): bool
    {
        if (self::isStoreOwner($user, $storeId)) {
            return true;
        }

        $userPermissions = self::getPermissions($user, $storeId);
        if (in_array('*', $userPermissions, true)) {
            return true;
        }

        foreach ($permissions as $p) {
            if (in_array($p, $userPermissions, true)) {
                return true;
            }
        }

        return false;
    }
}
