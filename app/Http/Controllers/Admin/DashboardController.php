<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Shared common data across admin pages.
     */
    protected function getSharedData(): array
    {
        return [
            'currentStore' => [
                'name' => 'Toko Kopi & Fashion Andi',
                'status' => 'active',
            ],
            'currentUser' => [
                'name' => 'Adam Bagas',
                'role' => 'Owner',
                'initials' => 'AB',
            ],
            'newOrders' => [
                'total_count' => 3,
            ],
            'lastSyncTime' => '09:30 WIB',
        ];
    }

    /**
     * Display the Admin Dashboard.
     */
    public function index(): View
    {
        $shared = $this->getSharedData();

        $kpis = [
            'revenue_today' => [
                'title' => 'Omzet Hari Ini',
                'value' => 'Rp 1.250.000',
                'raw_value' => 1250000,
                'growth' => '+8,7%',
                'comparison' => 'vs. kemarin',
                'is_positive' => true,
            ],
            'sales_today' => [
                'title' => 'Penjualan',
                'value' => '32',
                'unit' => 'Transaksi',
                'growth' => '+4 transaksi',
                'comparison' => 'vs. kemarin',
                'is_positive' => true,
            ],
            'profit_today' => [
                'title' => 'Keuntungan',
                'value' => 'Rp 850.000',
                'raw_value' => 850000,
                'subtitle' => 'Margin laba 68%',
            ],
            'low_stock' => [
                'title' => 'Stok Menipis',
                'value' => '5',
                'unit' => 'Produk',
                'subtitle' => 'Perlu segera restock',
            ],
        ];

        $bestSeller = [
            'badge' => '#1 TERLARIS',
            'name' => 'Coffe Americano',
            'price' => 'Rp 15.000',
            'category' => 'Makanan & Minuman',
            'sold_count' => '18 terjual hari ini',
            'image' => asset('images/dashboard/coffee-americano.jpg'),
            'other_popular' => [
                [
                    'name' => 'Matcha Latte',
                    'image' => asset('images/dashboard/matcha-latte.jpg'),
                ],
                [
                    'name' => 'Tas Anyaman',
                    'image' => asset('images/dashboard/tas-anyaman.jpg'),
                ],
                [
                    'name' => 'Fries',
                    'image' => asset('images/dashboard/fries.jpg'),
                ],
                [
                    'name' => 'T-Shirt',
                    'image' => asset('images/dashboard/tshirt.jpg'),
                ],
            ],
        ];

        $newOrders = [
            'total_count' => 3,
            'alert_message' => 'Segera konfirmasi pesanan pelanggan.',
            'items' => [
                [
                    'order_number' => '#ORD-0092',
                    'time' => '09:25',
                    'items_summary' => 'Coffe Americano x2',
                    'total' => 'Rp 30.000',
                    'status' => 'Menunggu',
                ],
                [
                    'order_number' => '#ORD-0091',
                    'time' => '09:18',
                    'items_summary' => 'Matcha Latte x3',
                    'total' => 'Rp 45.000',
                    'status' => 'Menunggu',
                ],
                [
                    'order_number' => '#ORD-0090',
                    'time' => '09:12',
                    'items_summary' => 'T-Shirt x4',
                    'total' => 'Rp 100.000',
                    'status' => 'Menunggu',
                ],
            ],
        ];

        $recentActivities = [
            [
                'title' => 'Pesanan dalam pengiriman',
                'meta' => '#ORD-0088 · GrabExpress',
                'time' => '09:28',
                'type' => 'delivery',
                'icon_bg' => 'bg-blue-100',
                'icon_text' => 'text-blue-600',
            ],
            [
                'title' => 'Transaksi kasir berhasil',
                'meta' => '#POS-0032 · Rp 45.000 · QRIS',
                'time' => '09:20',
                'type' => 'success',
                'icon_bg' => 'bg-emerald-100',
                'icon_text' => 'text-emerald-600',
            ],
            [
                'title' => 'Pesanan diterima pelanggan',
                'meta' => '#ORD-0086 · Selesai',
                'time' => '09:10',
                'type' => 'received',
                'icon_bg' => 'bg-emerald-100',
                'icon_text' => 'text-emerald-600',
            ],
            [
                'title' => 'Pembayaran diterima',
                'meta' => '#ORD-0089 · Rp 46.000',
                'time' => '09:05',
                'type' => 'payment',
                'icon_bg' => 'bg-blue-100',
                'icon_text' => 'text-blue-600',
            ],
        ];

        $quickPosProducts = [
            [
                'name' => 'Americano',
                'price' => 'Rp 15.000',
                'image' => asset('images/dashboard/coffee-americano.jpg'),
            ],
            [
                'name' => 'Matcha Latte',
                'price' => 'Rp 15.000',
                'image' => asset('images/dashboard/matcha-latte.jpg'),
            ],
            [
                'name' => 'Fries',
                'price' => 'Rp 22.000',
                'image' => asset('images/dashboard/fries.jpg'),
            ],
            [
                'name' => 'T-Shirt',
                'price' => 'Rp 25.000',
                'image' => asset('images/dashboard/tshirt.jpg'),
            ],
        ];

        $productsSummary = [
            'total_active' => 124,
            'categories' => [
                ['name' => 'Makanan & Minuman', 'count' => 86],
                ['name' => 'Fashion', 'count' => 38],
            ],
            'low_stock_count' => 5,
        ];

        $salesReport = [
            'period' => 'Omzet 7 hari terakhir',
            'total_revenue' => 'Rp 8.750.000',
            'growth' => '+12%',
            'date_range' => '14–20 Okt 2026',
            'chart_points' => [
                ['day' => 'Rab', 'x' => 0, 'y' => 72],
                ['day' => 'Kam', 'x' => 45, 'y' => 55],
                ['day' => 'Jum', 'x' => 90, 'y' => 62],
                ['day' => 'Sab', 'x' => 135, 'y' => 35],
                ['day' => 'Min', 'x' => 180, 'y' => 45],
                ['day' => 'Sen', 'x' => 225, 'y' => 25],
                ['day' => 'Sel', 'x' => 270, 'y' => 45, 'is_today' => true],
            ],
        ];

        $currentDate = 'Hari ini, 20 Okt 2026';

        return view('admin.dashboard', array_merge($shared, compact(
            'kpis',
            'bestSeller',
            'newOrders',
            'recentActivities',
            'quickPosProducts',
            'productsSummary',
            'salesReport',
            'currentDate'
        )));
    }

    /**
     * Display POS / Kasir Screen.
     */
    public function pos(): View
    {
        return view('admin.pos', $this->getSharedData());
    }

    /**
     * Display Inventory Screen.
     */
    public function inventory(): View
    {
        return view('admin.inventory', $this->getSharedData());
    }

    /**
     * Display Products Screen.
     */
    public function products(): View
    {
        return view('admin.products', $this->getSharedData());
    }

    /**
     * Display Finance / Keuangan Screen.
     */
    public function finance(): View
    {
        return view('admin.finance', $this->getSharedData());
    }

    /**
     * Display Customers / Pelanggan Screen.
     */
    public function customers(): View
    {
        return view('admin.customers', $this->getSharedData());
    }

    /**
     * Display Online Orders / Pesanan Online Screen.
     */
    public function orders(): View
    {
        return view('admin.orders', $this->getSharedData());
    }
}
