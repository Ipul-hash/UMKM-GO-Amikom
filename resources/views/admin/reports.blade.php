@extends('layouts.admin')

@section('title', 'UMKMGo - Laporan Penjualan')

@section('content')
<main class="p-8 space-y-6" x-data="reportManagement()" x-init="init()">
    <!-- Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border text-sm font-medium"
         :class="toast.type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-red-50 text-red-800 border-red-200'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.show = false" class="text-slate-400 hover:text-slate-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </button>
    </div>

    <!-- BEGIN: Content Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Laporan Penjualan</h1>
            <p class="text-sm text-gray-500 mt-1">Analisis performa bisnis dan penjualan</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <!-- Date Filter Picker -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center gap-2.5 bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-700 shadow-sm cursor-pointer hover:border-gray-300 transition">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
                        <line x1="16" x2="16" y1="2" y2="6"></line>
                        <line x1="8" x2="8" y1="2" y2="6"></line>
                        <line x1="3" x2="21" y1="10" y2="10"></line>
                    </svg>
                    <span x-text="summary.period_label || 'Bulan Ini'">01 Okt 2026 - 31 Okt 2026</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" 
                     class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-20 text-xs font-medium"
                     style="display: none;">
                    <button @click="setPeriod('today'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === 'today' ? 'font-bold text-blue-600' : 'text-slate-700'">Hari Ini</button>
                    <button @click="setPeriod('7_days'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === '7_days' ? 'font-bold text-blue-600' : 'text-slate-700'">7 Hari Terakhir</button>
                    <button @click="setPeriod('30_days'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === '30_days' ? 'font-bold text-blue-600' : 'text-slate-700'">30 Hari Terakhir</button>
                    <button @click="setPeriod('this_month'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === 'this_month' ? 'font-bold text-blue-600' : 'text-slate-700'">Bulan Ini</button>
                    <button @click="setPeriod('last_month'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === 'last_month' ? 'font-bold text-blue-600' : 'text-slate-700'">Bulan Lalu</button>
                    <button @click="setPeriod('this_year'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === 'this_year' ? 'font-bold text-blue-600' : 'text-slate-700'">Tahun Ini</button>
                </div>
            </div>

            <!-- Export Button -->
            <button @click="exportCsv()" type="button" class="flex items-center gap-2 bg-[#2563EB] hover:bg-blue-700 text-white font-medium text-xs px-4 py-2.5 rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                Export CSV / Excel
            </button>
        </div>
    </div>
    <!-- END: Content Header Section -->

    <!-- BEGIN: Revenue Trend Card -->
    <section class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Tren Omzet Penjualan</span>
                <div class="flex items-center gap-3 mt-1.5">
                    <span class="text-2xl font-bold text-gray-900 tracking-tight" x-text="summary.total_omzet_formatted">Rp 0</span>
                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-xs font-bold px-2 py-0.5 rounded-md">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span x-text="(summary.growth_percentage >= 0 ? '+' : '') + summary.growth_percentage + '% vs periode lalu'">+12% vs periode lalu</span>
                    </span>
                </div>
            </div>
            <!-- Legend Indicator -->
            <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
                <span class="w-2.5 h-2.5 rounded-sm bg-blue-600"></span>
                <span>Omzet harian</span>
            </div>
        </div>

        <!-- Trend Line SVG Chart -->
        <div class="mt-6 w-full h-[180px]">
            <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 1000 180">
                <defs>
                    <linearGradient id="gradientOmzet" x1="0" x2="0" y1="0" y2="1">
                        <stop offset="0%" stop-color="#2563EB" stop-opacity="0.12"></stop>
                        <stop offset="100%" stop-color="#2563EB" stop-opacity="0.0"></stop>
                    </linearGradient>
                </defs>
                <!-- Area Gradient Fill -->
                <path :d="chart.area_path" fill="url(#gradientOmzet)"></path>
                <!-- Continuous Trend Curve -->
                <path :d="chart.trend_path" fill="none" stroke="#2563EB" stroke-linecap="round" stroke-width="2.5"></path>
            </svg>
        </div>

        <!-- Date Axis Labels -->
        <div class="flex justify-between items-center text-[11px] text-gray-400 font-medium pt-3 border-t border-gray-100">
            <template x-for="(lbl, idx) in chart.axis_labels" :key="idx">
                <span x-text="lbl"></span>
            </template>
        </div>

        <!-- Chart Footnote -->
        <p class="text-[11px] text-gray-400 mt-2">Grafik Tren Penjualan Harian · Data terhubung ke POS & Pesanan Online</p>
    </section>
    <!-- END: Revenue Trend Card -->

    <!-- BEGIN: Bottom Analytics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Selling Products Card -->
        <section class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 tracking-tight mb-4">5 Produk Terlaris</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/80 text-[11px] font-semibold text-gray-500 uppercase tracking-wider rounded-lg">
                                <th class="py-2.5 px-3 rounded-l-lg w-12 text-center">No.</th>
                                <th class="py-2.5 px-3">Nama produk</th>
                                <th class="py-2.5 px-3 text-right rounded-r-lg">Terjual</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            <template x-for="prod in topProducts" :key="prod.rank">
                                <tr>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md font-bold text-[11px]"
                                              :class="prod.rank === 1 ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500'"
                                              x-text="prod.rank">1</span>
                                    </td>
                                    <td class="py-3 px-3 font-semibold text-gray-800" x-text="prod.name">Americano</td>
                                    <td class="py-3 px-3 text-right font-bold text-gray-900" x-text="prod.sold">142 porsi</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-100 mt-6 text-[11px] text-gray-400">
                Menampilkan <span x-text="topProducts.length">0</span> produk
            </div>
        </section>

        <!-- Payment Methods Card -->
        <section class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 tracking-tight mb-4">Metode Pembayaran</h3>
                <div class="flex items-center justify-around gap-6 pt-2">
                    <!-- Circular Donut Chart -->
                    <div class="relative w-40 h-40 flex items-center justify-center">
                        <div class="w-full h-full rounded-full transition-all duration-500" :style="getDonutGradient()"></div>
                        <!-- Inner cutout for donut style -->
                        <div class="absolute w-28 h-28 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
                            <span class="text-xl font-bold text-gray-900 leading-tight">100%</span>
                            <span class="text-[10px] text-gray-400 font-medium">Pembayaran</span>
                        </div>
                    </div>

                    <!-- Payment Legend & Stats -->
                    <div class="space-y-3.5 min-w-[150px]">
                        <template x-for="p in payments" :key="p.key">
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-2.5 h-2.5 rounded-sm" :style="`background-color: ${p.color};`"></span>
                                    <span class="text-gray-600 font-medium" x-text="p.label">QRIS</span>
                                </div>
                                <span class="font-bold text-gray-900 ml-4" x-text="p.percentage + '%'">0%</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <!-- Empty space filler -->
            <div class="h-4"></div>
        </section>
    </div>
    <!-- END: Bottom Analytics Grid -->
</main>

<script>
function reportManagement() {
    return {
        period: 'this_month',
        loading: false,
        summary: {
            total_omzet_formatted: 'Rp 0',
            growth_percentage: 12.0,
            period_label: 'Bulan Ini',
            total_transactions: 0
        },
        chart: {
            points: [],
            trend_path: 'M 0 170 C 250 140, 500 110, 1000 70',
            area_path: 'M 0 180 L 0 170 C 250 140, 500 110, 1000 70 L 1000 180 Z',
            axis_labels: ['01 Okt', '06 Okt', '11 Okt', '16 Okt', '21 Okt', '26 Okt', '31 Okt']
        },
        topProducts: [],
        payments: [],
        toast: {
            show: false,
            message: '',
            type: 'success'
        },
        showNotification(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => { this.toast.show = false; }, 3500);
        },
        setPeriod(p) {
            this.period = p;
            this.fetchReports();
        },
        getDonutGradient() {
            if (!this.payments || this.payments.length === 0) {
                return 'background: conic-gradient(#2563EB 0% 45%, #60A5FA 45% 75%, #BFDBFE 75% 100%)';
            }
            let qris = this.payments.find(p => p.key === 'qris')?.percentage || 45;
            let tunai = this.payments.find(p => p.key === 'tunai')?.percentage || 30;
            let p1 = qris;
            let p2 = Math.min(100, p1 + tunai);
            return `background: conic-gradient(#2563EB 0% ${p1}%, #60A5FA ${p1}% ${p2}%, #BFDBFE ${p2}% 100%)`;
        },
        async init() {
            await this.fetchReports();
        },
        async fetchReports() {
            this.loading = true;
            try {
                const res = await fetch(`/api/v1/laporan/penjualan?period=${this.period}`);
                const json = await res.json();
                if (json.success) {
                    this.summary = json.summary;
                    if (json.chart.trend_path) {
                        this.chart = json.chart;
                    }
                    this.topProducts = json.top_products;
                    this.payments = json.payments;
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Gagal memuat laporan penjualan', 'error');
            } finally {
                this.loading = false;
            }
        },
        exportCsv() {
            window.location.href = `/api/v1/laporan/penjualan/export-csv?period=${this.period}`;
            this.showNotification('Mengunduh laporan penjualan (CSV)...');
        }
    };
}
</script>
@endsection
