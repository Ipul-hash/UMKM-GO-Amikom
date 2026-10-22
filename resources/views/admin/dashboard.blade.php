@extends('layouts.admin')

@section('title', 'UMKMGo - Dashboard Admin')

@section('content')
    <!-- Header Title & Date Pill -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Selamat pagi, {{ explode(' ', $currentUser['name'] ?? 'Adam')[0] }}! Ini ringkasan performa tokomu hari ini.
            </p>
        </div>

        <!-- Date Filter Pill -->
        <button class="inline-flex items-center gap-2.5 px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 hover:bg-slate-50 shadow-sm transition-colors self-start sm:self-auto" type="button">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
                <line x1="16" x2="16" y1="2" y2="6"></line>
                <line x1="8" x2="8" y1="2" y2="6"></line>
                <line x1="3" x2="21" y1="10" y2="10"></line>
            </svg>
            <span>{{ $currentDate ?? 'Hari ini, 20 Okt 2026' }}</span>
            <svg class="w-3.5 h-3.5 text-slate-400 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6"></path>
            </svg>
        </button>
    </section>

    <!-- BEGIN: Row 1 - 4 KPI Cards -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1: Omzet Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-xs font-medium text-slate-500">{{ $kpis['revenue_today']['title'] }}</span>
                <div class="text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect height="14" rx="2" width="20" x="2" y="5"></rect>
                        <path d="M16 12h.01M2 10h20"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $kpis['revenue_today']['value'] }}</div>
                <div class="mt-2 text-xs font-semibold text-emerald-600 flex items-center gap-1">
                    <span>{{ $kpis['revenue_today']['growth'] }}</span>
                    <span class="font-normal text-slate-400">{{ $kpis['revenue_today']['comparison'] }}</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Penjualan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-xs font-medium text-slate-500">{{ $kpis['sales_today']['title'] }}</span>
                <div class="text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" x2="8" y1="13" y2="13"></line>
                        <line x1="16" x2="8" y1="17" y2="17"></line>
                        <line x1="10" x2="8" y1="9" y2="9"></line>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $kpis['sales_today']['value'] }}</span>
                    <span class="text-sm font-medium text-slate-500">{{ $kpis['sales_today']['unit'] }}</span>
                </div>
                <div class="mt-2 text-xs font-semibold text-emerald-600 flex items-center gap-1">
                    <span>{{ $kpis['sales_today']['growth'] }}</span>
                    <span class="font-normal text-slate-400">{{ $kpis['sales_today']['comparison'] }}</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Keuntungan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-xs font-medium text-slate-500">{{ $kpis['profit_today']['title'] }}</span>
                <div class="text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $kpis['profit_today']['value'] }}</div>
                <div class="mt-2 text-xs text-slate-500">
                    {{ $kpis['profit_today']['subtitle'] }}
                </div>
            </div>
        </div>

        <!-- KPI 4: Stok Menipis -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-xs font-medium text-slate-500">{{ $kpis['low_stock']['title'] }}</span>
                <div class="text-amber-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                        <path d="m3.3 7 8.7 5 8.7-5M12 22V12"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-bold text-amber-500 tracking-tight">{{ $kpis['low_stock']['value'] }}</span>
                    <span class="text-sm font-medium text-amber-600">{{ $kpis['low_stock']['unit'] }}</span>
                </div>
                <div class="mt-2 text-xs text-amber-600/90 font-medium">
                    {{ $kpis['low_stock']['subtitle'] }}
                </div>
            </div>
        </div>
    </section>
    <!-- END: Row 1 -->

    <!-- BEGIN: Row 2 - 3 Feature Cards -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Card 1: Produk Terlaris -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3">
                    <h2 class="font-bold text-sm text-slate-900">Produk Terlaris</h2>
                    <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Lihat semua</a>
                </div>

                <!-- Best Seller Main Item -->
                <div class="flex gap-4 mt-2">
                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                        <img src="{{ $bestSeller['image'] }}" alt="{{ $bestSeller['name'] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-center">
                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-blue-100 text-blue-700 rounded-md tracking-wider w-max mb-1">
                            {{ $bestSeller['badge'] }}
                        </span>
                        <h3 class="font-bold text-slate-900 text-sm">{{ $bestSeller['name'] }}</h3>
                        <div class="text-sm font-bold text-blue-600 mt-0.5">{{ $bestSeller['price'] }}</div>
                        <div class="text-[11px] text-slate-400 mt-1">
                            {{ $bestSeller['category'] }}<br>{{ $bestSeller['sold_count'] }}
                        </div>
                    </div>
                </div>

                <!-- Other Popular Items -->
                <div class="mt-5 pt-4 border-t border-slate-100">
                    <div class="text-[11px] text-slate-400 font-medium mb-3">Juga diminati pelanggan</div>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach ($bestSeller['other_popular'] as $item)
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 mb-1.5">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-[11px] font-medium text-slate-700 truncate w-full">{{ $item['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Pesanan Baru -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3">
                    <h2 class="font-bold text-sm text-slate-900">Pesanan Baru ({{ $newOrders['total_count'] }})</h2>
                    <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Lihat semua</a>
                </div>

                <!-- Orders List -->
                <div class="divide-y divide-slate-100">
                    @foreach ($newOrders['items'] as $order)
                        <div class="py-3 first:pt-1">
                            <div class="flex items-center justify-between text-xs">
                                <div class="font-bold text-slate-800">
                                    {{ $order['order_number'] }}
                                    <span class="font-normal text-slate-400 ml-1.5">{{ $order['time'] }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-600">
                                    {{ $order['status'] }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-1 text-xs">
                                <span class="text-slate-500 font-medium">{{ $order['items_summary'] }}</span>
                                <span class="font-bold text-slate-900">{{ $order['total'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Alert message box -->
            <div class="mt-4 p-3 bg-blue-50/70 border border-blue-100 rounded-xl flex items-center gap-2 text-xs text-blue-600">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span class="font-medium">{{ $newOrders['alert_message'] }}</span>
            </div>
        </div>

        <!-- Card 3: Aktivitas Terakhir -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3">
                    <h2 class="font-bold text-sm text-slate-900">Aktivitas Terakhir</h2>
                    <button class="text-slate-400 hover:text-slate-600" type="button" aria-label="Menu aktivitas">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="5" cy="12" r="2"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                            <circle cx="19" cy="12" r="2"></circle>
                        </svg>
                    </button>
                </div>

                <!-- Activity Items with Vertical Tracker -->
                <div class="relative pl-6 space-y-4 mt-2">
                    <!-- Timeline vertical line -->
                    <div class="absolute left-2.5 top-2 bottom-2 w-px bg-slate-200"></div>

                    @foreach ($recentActivities as $activity)
                        <div class="relative">
                            <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full {{ $activity['icon_bg'] }} flex items-center justify-center {{ $activity['icon_text'] }}">
                                @if ($activity['type'] === 'delivery')
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <rect height="13" width="15" x="1" y="3"></rect>
                                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    </svg>
                                @elseif ($activity['type'] === 'success')
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                @elseif ($activity['type'] === 'received')
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    </svg>
                                @else
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <rect height="14" rx="2" width="20" x="2" y="5"></rect>
                                        <line x1="2" x2="22" y1="10" y2="10"></line>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex justify-between items-start text-xs">
                                <div>
                                    <p class="font-medium text-slate-800">{{ $activity['title'] }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $activity['meta'] }}</p>
                                </div>
                                <span class="text-[11px] text-slate-400">{{ $activity['time'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="#" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-700 gap-1">
                    <span>Lihat seluruh aktivitas</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    <!-- END: Row 2 -->

    <!-- BEGIN: Row 3 - POS Quick Access, Kelola Produk, Laporan Penjualan -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Card 1: POS / Kasir Quick Access -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3">
                    <h2 class="font-bold text-sm text-slate-900">POS / Kasir Quick Access</h2>
                    <button class="text-slate-400 hover:text-slate-600" type="button" aria-label="Menu POS">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="5" cy="12" r="2"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                            <circle cx="19" cy="12" r="2"></circle>
                        </svg>
                    </button>
                </div>

                <!-- Search input bar -->
                <div class="relative mt-1 mb-4">
                    <input type="text" placeholder="Cari nama atau kode produk..." class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-8 py-2 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" x2="16.65" y1="21" y2="16.65"></line>
                    </svg>
                    <!-- Barcode scan icon -->
                    <svg class="w-4 h-4 text-slate-400 absolute right-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 5v4M3 5h4M21 5h-4M21 5v4M3 19v-4M3 19h4M21 19h-4M21 19v-4M7 9v6M11 9v6M14 9v6M17 9v6"></path>
                    </svg>
                </div>

                <!-- 2x2 Quick Items -->
                <div class="grid grid-cols-2 gap-2.5">
                    @foreach ($quickPosProducts as $item)
                        <div class="border border-slate-100 rounded-xl p-2 flex items-center gap-2 hover:bg-slate-50 transition-colors cursor-pointer">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            <div class="min-w-0">
                                <div class="text-xs font-semibold text-slate-800 truncate">{{ $item['name'] }}</div>
                                <div class="text-[11px] text-slate-500 font-medium">{{ $item['price'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Buka Kasir Button -->
            <button class="mt-4 w-full py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-colors" type="button">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect height="16" rx="2" width="20" x="2" y="4"></rect>
                    <path d="M7 15h10M2 9.5h20"></path>
                </svg>
                <span>Buka Kasir</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Card 2: Kelola Produk -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-2">
                    <h2 class="font-bold text-sm text-slate-900">Kelola Produk</h2>
                    <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Kelola</a>
                </div>

                <div class="flex items-center justify-between mt-2">
                    <div>
                        <span class="text-xs text-slate-400 font-medium">Total Produk</span>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $productsSummary['total_active'] }}</span>
                            <span class="text-xs text-slate-500 font-medium">barang aktif</span>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" x2="12" y1="22.08" y2="12"></line>
                        </svg>
                    </div>
                </div>

                <!-- Categories tags -->
                <div class="flex items-center gap-2 mt-4 flex-wrap">
                    @foreach ($productsSummary['categories'] as $cat)
                        <span class="px-2.5 py-1 text-[11px] font-medium bg-slate-100 text-slate-600 rounded-lg">
                            {{ $cat['name'] }} · {{ $cat['count'] }}
                        </span>
                    @endforeach
                </div>

                <!-- Warning Callout -->
                <div class="mt-4 p-3 bg-amber-50/60 border border-amber-100/80 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="text-amber-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                <line x1="12" x2="12" y1="9" y2="13"></line>
                                <line x1="12" x2="12.01" y1="17" y2="17"></line>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-amber-800">{{ $productsSummary['low_stock_count'] }} stok menipis</div>
                            <div class="text-[11px] text-amber-700/80">Cek persediaan sebelum kehabisan.</div>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </div>
            </div>

            <!-- + Tambah Produk Baru Button -->
            <button class="mt-4 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-colors shadow-sm shadow-blue-600/20" type="button">
                <span>+ Tambah Produk Baru</span>
            </button>
        </div>

        <!-- Card 3: Laporan Penjualan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-1">
                    <h2 class="font-bold text-sm text-slate-900">Laporan Penjualan</h2>
                    <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Detail laporan</a>
                </div>

                <div class="mt-2">
                    <span class="text-xs text-slate-400 font-medium">{{ $salesReport['period'] }}</span>
                    <div class="flex items-center gap-2.5 mt-0.5">
                        <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $salesReport['total_revenue'] }}</span>
                        <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[11px] font-bold flex items-center">
                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                <polyline points="17 6 23 6 23 12"></polyline>
                            </svg>
                            {{ $salesReport['growth'] }}
                        </span>
                    </div>
                </div>

                <!-- SVG Line Chart Container -->
                <div class="relative w-full h-32 mt-4">
                    <!-- Y-axis guide labels -->
                    <div class="absolute left-0 top-0 text-[10px] text-slate-400">2 jt</div>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 text-[10px] text-slate-400">1 jt</div>
                    <div class="absolute left-0 bottom-6 text-[10px] text-slate-400">0</div>

                    <!-- Chart SVG Canvas -->
                    <div class="ml-6 h-24 relative">
                        <!-- Background Grid Lines -->
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                            <div class="w-full border-b border-slate-100"></div>
                            <div class="w-full border-b border-slate-100"></div>
                            <div class="w-full border-b border-slate-100"></div>
                        </div>

                        <!-- SVG Area & Line -->
                        <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 300 100">
                            <defs>
                                <linearGradient id="chartGradient" x1="0" x2="0" y1="0" y2="1">
                                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.15"></stop>
                                    <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.0"></stop>
                                </linearGradient>
                            </defs>
                            <!-- Filled Area -->
                            <path d="M 0,72 L 45,55 L 90,62 L 135,35 L 180,45 L 225,25 L 270,45 L 270,100 L 0,100 Z" fill="url(#chartGradient)"></path>
                            <!-- Smooth Line Path -->
                            <path d="M 0,72 L 45,55 L 90,62 L 135,35 L 180,45 L 225,25 L 270,45" fill="none" stroke="#2563EB" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                            <!-- Data points -->
                            @foreach ($salesReport['chart_points'] as $pt)
                                <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="3.5" class="fill-blue-600 stroke-white stroke-2"></circle>
                            @endforeach
                        </svg>

                        <!-- X Axis Labels -->
                        <div class="flex justify-between text-[10px] text-slate-400 font-medium pt-2">
                            @foreach ($salesReport['chart_points'] as $pt)
                                <span class="{{ !empty($pt['is_today']) ? 'text-blue-600 font-bold' : '' }}">
                                    {{ $pt['day'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Footer -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <div class="flex items-center gap-1.5 font-medium text-slate-600">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <span>Omzet harian</span>
                </div>
                <span>{{ $salesReport['date_range'] }}</span>
            </div>
        </div>
    </section>
    <!-- END: Row 3 -->
@endsection
