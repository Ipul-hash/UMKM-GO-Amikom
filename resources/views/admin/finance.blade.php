@extends('layouts.admin')

@section('title', 'UMKMGo - Keuangan')

@section('content')
<main class="p-8 space-y-6" data-purpose="dashboard-content" x-data="financeManagement()" x-init="init()">
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

    <!-- Header Section with Filters & Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 leading-none">Keuangan</h1>
            <p class="text-sm text-slate-500 mt-1.5 font-normal">Pantau arus kas, pendapatan, dan pengeluaran</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Periode Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="bg-white border border-slate-200 text-slate-700 px-3.5 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 hover:bg-slate-50 shadow-sm transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect height="18" rx="2" ry="2" stroke-width="1.8" width="18" x="3" y="4"></rect>
                        <line stroke-width="1.8" x1="16" x2="16" y1="2" y2="6"></line>
                        <line stroke-width="1.8" x1="8" x2="8" y1="2" y2="6"></line>
                        <line stroke-width="1.8" x1="3" x2="21" y1="10" y2="10"></line>
                    </svg>
                    <span x-text="periodLabel()">Bulan Ini</span>
                    <svg class="w-3.5 h-3.5 text-slate-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-20 text-xs font-medium" style="display: none;">
                    <button @click="setPeriod('today'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === 'today' ? 'font-bold text-blue-600' : 'text-slate-700'">Hari Ini</button>
                    <button @click="setPeriod('this_week'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === 'this_week' ? 'font-bold text-blue-600' : 'text-slate-700'">Minggu Ini</button>
                    <button @click="setPeriod('this_month'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === 'this_month' ? 'font-bold text-blue-600' : 'text-slate-700'">Bulan Ini</button>
                    <button @click="setPeriod('last_month'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === 'last_month' ? 'font-bold text-blue-600' : 'text-slate-700'">Bulan Lalu</button>
                    <button @click="setPeriod('this_year'); open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="period === 'this_year' ? 'font-bold text-blue-600' : 'text-slate-700'">Tahun Ini</button>
                </div>
            </div>

            <!-- Primary Action Button -->
            <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
                <span>+ Catat Transaksi</span>
            </button>
        </div>
    </div>

    <!-- BEGIN: 4 Summary KPI Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" data-purpose="kpi-cards">
        <!-- Card 1: Total Saldo -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-xs font-medium text-slate-500">Total Saldo</span>
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect height="16" rx="2" stroke-width="1.8" width="20" x="2" y="4"></rect>
                    <circle cx="16" cy="12" fill="currentColor" r="1.5"></circle>
                </svg>
            </div>
            <div class="mt-3">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight" x-text="summary.total_balance_formatted">Rp 0</h3>
                <div class="flex items-center gap-1 text-[11px] font-medium text-emerald-600 mt-2">
                    <span class="font-semibold" x-text="(summary.growth_rate >= 0 ? '↗ +' : '↘ ') + summary.growth_rate + '%'">+8.4%</span>
                    <span class="text-slate-400 font-normal">dibanding periode lalu</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Pendapatan -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-xs font-medium text-slate-500">Pendapatan</span>
                <svg class="w-4 h-4 text-blue-600 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 5L5 19M5 19h10M5 19V9" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <div class="mt-3">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight" x-text="summary.income_formatted">Rp 0</h3>
                <p class="text-[11px] text-slate-400 mt-2">Total pemasukan periode ini</p>
            </div>
        </div>

        <!-- Card 3: Pengeluaran -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-xs font-medium text-slate-500">Pengeluaran</span>
                <svg class="w-4 h-4 text-red-500 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M5 19L19 5M19 5H9M19 5v10" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <div class="mt-3">
                <h3 class="text-xl font-bold text-red-600 tracking-tight" x-text="summary.expense_formatted">Rp 0</h3>
                <p class="text-[11px] text-slate-400 mt-2">Total pengeluaran periode ini</p>
            </div>
        </div>

        <!-- Card 4: Laba Bersih -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-xs font-medium text-slate-500">Laba Bersih</span>
                <svg class="w-4 h-4 text-blue-600 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 17l6-6 4 4 8-8M17 7h4v4" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <div class="mt-3">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight" x-text="summary.net_profit_formatted">Rp 0</h3>
                <p class="text-[11px] text-slate-400 mt-2">Pendapatan dikurangi pengeluaran</p>
            </div>
        </div>
    </section>
    <!-- END: 4 Summary KPI Cards -->

    <!-- BEGIN: Two Column Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Column Left: Grafik Arus Kas & Informasi Rekening (Span 2) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card: Grafik Arus Kas -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm" data-purpose="cash-flow-chart">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Grafik Arus Kas</h2>
                        <p class="text-[11px] text-slate-400 mt-0.5">Ringkasan mingguan <span x-text="summary.period_label"></span></p>
                    </div>
                    <!-- Legends -->
                    <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-sm bg-blue-600"></span>
                            <span class="text-[11px]">Pendapatan</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-sm bg-slate-300"></span>
                            <span class="text-[11px]">Pengeluaran</span>
                        </div>
                    </div>
                </div>

                <!-- Custom Clean SVG/CSS Bar Chart -->
                <div class="relative pt-6 pb-2">
                    <div class="h-44 flex flex-col justify-between text-[11px] text-slate-400 relative">
                        <!-- Grid Lines -->
                        <div class="border-b border-slate-100 w-full flex items-center justify-between pb-1">
                            <span>Maks</span>
                        </div>
                        <div class="border-b border-slate-100 w-full flex items-center justify-between pb-1">
                            <span>75%</span>
                        </div>
                        <div class="border-b border-slate-100 w-full flex items-center justify-between pb-1">
                            <span>50%</span>
                        </div>
                        <div class="border-b border-slate-100 w-full flex items-center justify-between pb-1">
                            <span>25%</span>
                        </div>
                        <div class="border-b border-slate-200 w-full flex items-center justify-between pb-1">
                            <span>0</span>
                        </div>

                        <!-- Bars Overlay -->
                        <div class="absolute inset-0 left-12 right-6 bottom-0 flex items-end justify-around pb-[1px]">
                            <template x-for="(item, idx) in weeklyChart" :key="idx">
                                <div class="flex items-end gap-1.5 group relative">
                                    <!-- Blue Bar (Income) -->
                                    <div class="w-6 bg-blue-600 rounded-t-sm flex flex-col items-center justify-start relative transition-all duration-300"
                                         :style="`height: ${calculateBarHeight(item.income)}px;`">
                                        <span class="absolute -top-4 text-[10px] font-bold text-blue-600" x-text="item.income_million"></span>
                                    </div>
                                    <!-- Gray Bar (Expense) -->
                                    <div class="w-6 bg-slate-300 rounded-t-sm flex flex-col items-center justify-start relative transition-all duration-300"
                                         :style="`height: ${calculateBarHeight(item.expense)}px;`">
                                        <span class="absolute -top-4 text-[10px] font-medium text-slate-500" x-text="item.expense_million"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- X-Axis Labels -->
                    <div class="flex justify-around pl-12 pr-6 mt-3 text-[11px] font-medium text-slate-500">
                        <template x-for="(item, idx) in weeklyChart" :key="idx">
                            <span x-text="item.week">Minggu</span>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Card: Informasi Rekening -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm" data-purpose="bank-accounts">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-900">Informasi Rekening</h2>
                    <span class="text-[11px] font-medium text-slate-500" x-text="accounts.length + ' rekening aktif'">3 rekening aktif</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                    <template x-for="(acc, i) in accounts" :key="i">
                        <div :class="i === 0 ? 'pt-2 sm:pt-0 sm:pr-4' : (i === 1 ? 'pt-3 sm:pt-0 sm:px-6' : 'pt-3 sm:pt-0 sm:pl-6')">
                            <div class="flex items-center gap-2 text-slate-600 mb-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect height="12" rx="2" stroke-width="1.8" width="20" x="2" y="6"></rect>
                                    <circle cx="12" cy="12" r="2.5" stroke-width="1.8"></circle>
                                </svg>
                                <span class="text-xs font-semibold text-slate-700" x-text="acc.name">Kas</span>
                            </div>
                            <div class="text-base font-bold text-slate-900" x-text="acc.formatted">Rp 0</div>
                            <p class="text-[11px] text-slate-400 mt-0.5" x-text="acc.type">Keterangan rekening</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Column Right: Riwayat Transaksi Terbaru (Span 1) -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm flex flex-col justify-between" data-purpose="recent-transactions">
            <div>
                <div class="mb-4">
                    <h2 class="text-sm font-bold text-slate-900">Riwayat Transaksi Terbaru</h2>
                    <div class="mt-1 text-[11px] font-medium text-slate-500">
                        <span>Paling baru dicatat</span>
                    </div>
                </div>

                <!-- Transaction List -->
                <div class="divide-y divide-slate-100 max-h-[360px] overflow-y-auto">
                    <template x-for="trx in transactions.slice(0, 5)" :key="trx.id">
                        <div class="py-3.5 flex items-center justify-between first:pt-1">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                     :class="trx.direction === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500'">
                                    <svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path x-show="trx.direction === 'income'" d="M19 5L5 19M5 19h10M5 19V9" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path x-show="trx.direction === 'expense'" d="M5 19L19 5M19 5H9M19 5v10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 leading-tight" x-text="trx.description || trx.category">Deskripsi</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5" x-text="trx.category"></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-400 block mb-0.5" x-text="trx.time">10.00</span>
                                <span class="text-xs font-bold"
                                      :class="trx.direction === 'income' ? 'text-emerald-600' : 'text-red-500'"
                                      x-text="trx.formatted_amount">+Rp 0</span>
                            </div>
                        </div>
                    </template>
                    <div x-show="transactions.length === 0" class="py-8 text-center text-xs text-slate-400">
                        Belum ada transaksi di periode ini.
                    </div>
                </div>
            </div>

            <!-- Bottom Link -->
            <div class="pt-4 border-t border-slate-100 text-center">
                <button @click="showAllModal = true" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700">
                    <span>Lihat Semua Transaksi</span>
                    <svg class="w-3.5 h-3.5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- END: Two Column Main Layout -->

    <!-- Modal Catat Transaksi Baru -->
    <div x-show="showCreateModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40"
         style="display: none;">
        <div @click.away="showCreateModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Catat Transaksi Keuangan</h3>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </button>
            </div>

            <form @submit.prevent="saveTransaction()" class="space-y-4 mt-4">
                <!-- Toggle Arah Transaksi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tipe Transaksi</label>
                    <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1 rounded-xl">
                        <button type="button" 
                                @click="form.direction = 'income'"
                                class="py-2 text-xs font-bold rounded-lg transition-all"
                                :class="form.direction === 'income' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                            Pemasukan (+)
                        </button>
                        <button type="button" 
                                @click="form.direction = 'expense'"
                                class="py-2 text-xs font-bold rounded-lg transition-all"
                                :class="form.direction === 'expense' ? 'bg-white text-red-500 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                            Pengeluaran (-)
                        </button>
                    </div>
                </div>

                <!-- Kategori Transaksi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori *</label>
                    <select x-model="form.category" required class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <template x-if="form.direction === 'income'">
                            <template x-for="cat in categories.income" :key="cat">
                                <option :value="cat" x-text="cat"></option>
                            </template>
                        </template>
                        <template x-if="form.direction === 'expense'">
                            <template x-for="cat in categories.expense" :key="cat">
                                <option :value="cat" x-text="cat"></option>
                            </template>
                        </template>
                    </select>
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal (Rp) *</label>
                    <input type="number" x-model="form.amount" required min="1" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Contoh: 150000">
                </div>

                <!-- Deskripsi / Keterangan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi / Keterangan</label>
                    <input type="text" x-model="form.description" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Contoh: Beli Biji Kopi Arabika 2kg">
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-[13px] font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" :disabled="loading" class="px-5 py-2 text-[13px] font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm disabled:opacity-50">
                        <span x-text="loading ? 'Menyimpan...' : 'Simpan Transaksi'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Lihat Semua Transaksi -->
    <div x-show="showAllModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40"
         style="display: none;">
        <div @click.away="showAllModal = false" class="bg-white rounded-2xl max-w-3xl w-full p-6 shadow-2xl border border-slate-100 max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 shrink-0">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Daftar Semua Transaksi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Catatan buku kas dan riwayat transaksi keuangan</p>
                </div>
                <button @click="showAllModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </button>
            </div>

            <div class="overflow-y-auto my-4 flex-1">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-2.5 px-3">Tanggal</th>
                            <th class="py-2.5 px-3">Kategori & Deskripsi</th>
                            <th class="py-2.5 px-3">Tipe</th>
                            <th class="py-2.5 px-3 text-right">Nominal</th>
                            <th class="py-2.5 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="t in transactions" :key="t.id">
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-3 whitespace-nowrap text-slate-600" x-text="t.entry_date"></td>
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-slate-900" x-text="t.category"></div>
                                    <div class="text-[11px] text-slate-400" x-text="t.description"></div>
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold"
                                          :class="t.direction === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'"
                                          x-text="t.direction === 'income' ? 'Pemasukan' : 'Pengeluaran'"></span>
                                </td>
                                <td class="py-3 px-3 text-right font-bold whitespace-nowrap"
                                    :class="t.direction === 'income' ? 'text-emerald-600' : 'text-red-500'"
                                    x-text="t.formatted_amount"></td>
                                <td class="py-3 px-3 text-center">
                                    <button @click="deleteTransaction(t.id)" class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end shrink-0">
                <button @click="showAllModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl">Tutup</button>
            </div>
        </div>
    </div>
</main>

<script>
function financeManagement() {
    return {
        period: 'this_month',
        loading: false,
        summary: {
            total_balance_formatted: 'Rp 0',
            income_formatted: 'Rp 0',
            expense_formatted: 'Rp 0',
            net_profit_formatted: 'Rp 0',
            growth_rate: 8.4,
            period_label: 'Bulan Ini'
        },
        accounts: [],
        weeklyChart: [],
        transactions: [],
        categories: {
            income: ['Penjualan Kasir POS', 'Penjualan Pesanan Online', 'Suntikan Modal Usaha', 'Pendapatan Lain-lain'],
            expense: ['Bahan Baku & Persediaan', 'Gaji & Upah Karyawan', 'Listrik, Air & Internet', 'Sewa Tempat Usaha', 'Pemasaran & Promo', 'Biaya Pemeliharaan & Operasional']
        },
        showCreateModal: false,
        showAllModal: false,
        form: {
            direction: 'income',
            category: 'Penjualan Kasir POS',
            amount: '',
            description: ''
        },
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
        periodLabel() {
            switch(this.period) {
                case 'today': return 'Hari Ini';
                case 'this_week': return 'Minggu Ini';
                case 'this_month': return 'Bulan Ini (' + this.summary.period_label + ')';
                case 'last_month': return 'Bulan Lalu';
                case 'this_year': return 'Tahun Ini';
                default: return 'Bulan Ini';
            }
        },
        setPeriod(p) {
            this.period = p;
            this.fetchFinance();
        },
        calculateBarHeight(val) {
            let max = 1;
            this.weeklyChart.forEach(w => {
                if (w.income > max) max = w.income;
                if (w.expense > max) max = w.expense;
            });
            return Math.min(150, Math.max(8, Math.round((val / max) * 150)));
        },
        async init() {
            await this.fetchCategories();
            await this.fetchFinance();
        },
        async fetchCategories() {
            try {
                const res = await fetch('/api/v1/laporan/keuangan/categories');
                const json = await res.json();
                if (json.success) {
                    this.categories.income = json.income;
                    this.categories.expense = json.expense;
                }
            } catch (e) {
                console.error(e);
            }
        },
        async fetchFinance() {
            this.loading = true;
            try {
                const res = await fetch(`/api/v1/laporan/keuangan?period=${this.period}`);
                const json = await res.json();
                if (json.success) {
                    this.summary = json.summary;
                    this.accounts = json.accounts;
                    this.weeklyChart = json.weekly_chart;
                    this.transactions = json.data;
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Gagal memuat data keuangan', 'error');
            } finally {
                this.loading = false;
            }
        },
        openCreateModal() {
            this.form = {
                direction: 'income',
                category: this.categories.income[0] || 'Penjualan Kasir POS',
                amount: '',
                description: ''
            };
            this.showCreateModal = true;
        },
        async saveTransaction() {
            this.loading = true;
            try {
                const res = await fetch('/api/v1/laporan/keuangan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.showNotification(json.message);
                    this.showCreateModal = false;
                    await this.fetchFinance();
                } else {
                    this.showNotification(json.message || 'Gagal menyimpan transaksi', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Terjadi kesalahan sistem', 'error');
            } finally {
                this.loading = false;
            }
        },
        async deleteTransaction(id) {
            if (!confirm('Hapus transaksi ini?')) return;
            try {
                const res = await fetch(`/api/v1/laporan/keuangan/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.showNotification(json.message);
                    await this.fetchFinance();
                } else {
                    this.showNotification('Gagal menghapus transaksi', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Terjadi kesalahan sistem', 'error');
            }
        }
    };
}
</script>
@endsection
