@extends('layouts.admin')

@section('title', 'UMKMGo - Inventory / Inventaris')

@section('content')
<main class="p-8 flex-1 flex flex-col justify-between" 
      data-purpose="inventory-page" 
      x-data="inventoryManagement()" 
      x-init="init()">
    <div>
        <!-- Title & Action Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Inventory / Inventaris</h1>
                <p class="text-sm text-slate-500 mt-1">Mengelola stok barang dan bahan baku secara real-time</p>
            </div>
            <div class="flex items-center gap-2.5">
                <button @click="openHistoryModal(null)" 
                        class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm px-4 py-2.5 rounded-xl border border-slate-200 shadow-xs transition-colors" 
                        type="button">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Riwayat Mutasi</span>
                </button>
                <button @click="openAdjustModal(null)" 
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl shadow-sm transition-colors" 
                        type="button">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    <span>Update Stok / Opname</span>
                </button>
            </div>
        </div>

        <!-- BEGIN: StatCards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" data-purpose="inventory-summary-cards">
            <!-- Total SKU -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                    <svg class="w-6 h-6 stroke-[1.8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-500">Total SKU</span>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-2xl font-bold text-slate-900" x-text="summary.total_sku || 0">0</span>
                        <span class="text-xs text-slate-500 font-normal">Varian</span>
                    </div>
                </div>
            </div>

            <!-- Stok Aman -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <svg class="w-6 h-6 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-500">Stok Aman</span>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-2xl font-bold text-slate-900" x-text="summary.safe_stock_count || 0">0</span>
                        <span class="text-xs text-slate-500 font-normal">SKU</span>
                    </div>
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0">
                    <svg class="w-6 h-6 stroke-[1.8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-500">Stok Menipis</span>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-2xl font-bold text-slate-900" x-text="summary.low_stock_count || 0">0</span>
                        <span class="text-xs text-slate-500 font-normal">SKU</span>
                    </div>
                </div>
            </div>

            <!-- Stok Kosong -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 flex-shrink-0">
                    <svg class="w-6 h-6 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M15 9l-6 6m0-6l6 6" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-500">Stok Kosong</span>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-2xl font-bold text-slate-900" x-text="summary.out_of_stock_count || 0">0</span>
                        <span class="text-xs text-slate-500 font-normal">SKU</span>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: StatCards -->

        <!-- BEGIN: FilterBar -->
        <section class="flex flex-wrap items-center justify-between gap-3 mb-4" data-purpose="inventory-filters">
            <div class="flex flex-wrap items-center gap-3 flex-1">
                <!-- Search Input -->
                <div class="relative w-full max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" x2="16.65" y1="21" y2="16.65"></line>
                        </svg>
                    </div>
                    <input x-model.debounce.350ms="searchQuery" 
                           @input="fetchInventory(1)"
                           class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-xs" 
                           placeholder="Cari SKU, nama produk, varian..." 
                           type="text"/>
                </div>

                <!-- Status Filter Dropdown -->
                <div class="relative">
                    <select x-model="statusFilter" 
                            @change="fetchInventory(1)" 
                            class="px-3.5 py-2 pr-8 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none">
                        <option value="">Semua Status Stok</option>
                        <option value="aman">Stok Aman</option>
                        <option value="menipis">Stok Menipis</option>
                        <option value="habis">Stok Habis</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                </div>

                <!-- Category Filter Dropdown -->
                <div class="relative">
                    <select x-model="selectedCategory" 
                            @change="fetchInventory(1)" 
                            class="px-3.5 py-2 pr-8 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none">
                        <option value="">Semua Kategori</option>
                        <template x-for="cat in categories" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name"></option>
                        </template>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Right Info / Total Value -->
            <div class="text-xs text-slate-500 bg-slate-100/80 px-3 py-1.5 rounded-lg border border-slate-200/60 hidden sm:block">
                Estimasi Aset: <strong class="text-slate-800 font-semibold" x-text="summary.total_asset_value_formatted || 'Rp 0'"></strong>
            </div>
        </section>
        <!-- END: FilterBar -->

        <!-- BEGIN: InventoryTable -->
        <section class="bg-white border border-slate-200/90 rounded-xl overflow-hidden shadow-[0_1px_3px_rgba(0,0,0,0.03)] relative min-h-[300px]" data-purpose="inventory-table-container">
            <!-- Loading Overlay -->
            <div x-show="loading" 
                 x-transition
                 class="absolute inset-0 bg-white/70 backdrop-blur-xs flex items-center justify-center z-10">
                <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-white border border-slate-200 shadow-md">
                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium text-slate-700">Memuat stok inventaris...</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-500 font-medium text-xs bg-slate-50/50">
                            <th class="py-3.5 px-6" scope="col">Nama &amp; SKU</th>
                            <th class="py-3.5 px-6" scope="col">Kategori</th>
                            <th class="py-3.5 px-6" scope="col">Stok Tersedia</th>
                            <th class="py-3.5 px-6" scope="col">Harga &amp; Nilai Aset</th>
                            <th class="py-3.5 px-6" scope="col">Status</th>
                            <th class="py-3.5 px-6 text-right" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="item in items" :key="item.id">
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <img :src="item.thumbnail" 
                                             :alt="item.product_name" 
                                             class="w-11 h-11 rounded-lg object-cover flex-shrink-0 border border-slate-100 shadow-xs bg-slate-50"
                                             onerror="this.src='/images/dashboard/coffee-americano.jpg'"/>
                                        <div>
                                            <div class="font-semibold text-slate-900 leading-snug">
                                                <span x-text="item.product_name"></span>
                                                <span class="text-xs text-slate-500 font-normal ml-1" x-text="'(' + item.variant_name + ')'"></span>
                                            </div>
                                            <div class="text-xs text-slate-400 font-mono mt-0.5" x-text="item.sku"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-slate-600" x-text="item.category_name"></td>
                                <td class="py-3 px-6">
                                    <span class="text-base font-bold" 
                                          :class="{
                                              'text-emerald-600': item.status === 'aman',
                                              'text-amber-500': item.status === 'menipis',
                                              'text-rose-600': item.status === 'habis'
                                          }"
                                          x-text="item.stock_quantity">
                                    </span>
                                    <span class="text-xs text-slate-400 font-normal ml-1">unit</span>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="font-medium text-slate-800 text-xs" x-text="item.price_formatted"></div>
                                    <div class="text-[11px] text-slate-400 mt-0.5" x-text="'Aset: ' + item.asset_value_formatted"></div>
                                </td>
                                <td class="py-3 px-6">
                                    <span :class="item.status_color" 
                                          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border">
                                        <span class="w-1.5 h-1.5 rounded-full" 
                                              :class="{
                                                  'bg-emerald-500': item.status === 'aman',
                                                  'bg-amber-500': item.status === 'menipis',
                                                  'bg-rose-500': item.status === 'habis'
                                              }"></span>
                                        <span x-text="item.status_label"></span>
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <div class="flex items-center justify-end gap-3 text-xs font-medium">
                                        <button @click="openAdjustModal(item)" 
                                                class="flex items-center gap-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded-lg transition-colors"
                                                type="button">
                                            <svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            <span>Update Stok</span>
                                        </button>
                                        <button @click="openHistoryModal(item)" 
                                                class="flex items-center gap-1 text-slate-500 hover:text-slate-800 hover:bg-slate-100 px-2 py-1 rounded-lg transition-colors"
                                                type="button">
                                            <svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            <span>History</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Empty State -->
                        <tr x-show="!loading && items.length === 0">
                            <td colspan="6" class="py-12 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-800">Tidak ada stok ditemukan</h3>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter status stok.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- END: InventoryTable -->
    </div>

    <!-- BEGIN: PaginationFooter -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 text-sm text-slate-500">
        <div>
            Menampilkan <span class="font-medium text-slate-700" x-text="pagination.from || 0"></span> - 
            <span class="font-medium text-slate-700" x-text="pagination.to || 0"></span> dari 
            <span class="font-medium text-slate-700" x-text="pagination.total || 0"></span> item inventaris
        </div>
        <div class="flex items-center gap-1.5">
            <!-- Prev Button -->
            <button @click="fetchInventory(pagination.current_page - 1)" 
                    :disabled="pagination.current_page <= 1"
                    :class="pagination.current_page <= 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 text-slate-700'"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 transition-colors" 
                    type="button">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
            
            <!-- Page Numbers -->
            <template x-for="p in pagination.last_page" :key="p">
                <button @click="fetchInventory(p)"
                        x-show="p === 1 || p === pagination.last_page || Math.abs(p - pagination.current_page) <= 1"
                        :class="p === pagination.current_page ? 'bg-blue-600 text-white shadow-sm font-medium' : 'hover:bg-slate-100 text-slate-600 font-normal'"
                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors text-sm" 
                        type="button" 
                        x-text="p">
                </button>
            </template>

            <!-- Next Button -->
            <button @click="fetchInventory(pagination.current_page + 1)" 
                    :disabled="pagination.current_page >= pagination.last_page"
                    :class="pagination.current_page >= pagination.last_page ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 text-slate-700'"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 transition-colors" 
                    type="button">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
    </div>
    <!-- END: PaginationFooter -->

    <!-- ==================== MODAL: UPDATE / PENYESUAIAN STOK ==================== -->
    <div x-show="modalAdjustOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalAdjustOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalAdjustOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="modalAdjustOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Penyesuaian Stok / Opname</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Catat barang masuk, barang keluar, atau stock opname fisik.</p>
                    </div>
                    <button @click="modalAdjustOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="submitAdjustment()" class="mt-4 space-y-4">
                    <!-- Variant Selector -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Varian Produk <span class="text-rose-500">*</span></label>
                        <select x-model="adjustForm.variant_id" 
                                @change="onVariantSelected()"
                                required 
                                class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Produk / Varian</option>
                            <template x-for="v in allVariantOptions" :key="v.id">
                                <option :value="v.id" x-text="v.sku + ' - ' + v.full_name + ' (Stok: ' + v.stock_quantity + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Jenis Penyesuaian -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Tipe Penyesuaian <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            <label :class="adjustForm.adjustment_type === 'in' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-800' : 'border-slate-200 text-slate-600 hover:bg-slate-50'"
                                   class="border rounded-xl p-2.5 flex flex-col items-center justify-center text-center cursor-pointer transition-colors">
                                <input type="radio" value="in" x-model="adjustForm.adjustment_type" class="sr-only"/>
                                <span class="text-xs font-semibold">Stok Masuk (+)</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Restock / Beli</span>
                            </label>

                            <label :class="adjustForm.adjustment_type === 'out' ? 'border-rose-500 bg-rose-50/50 text-rose-800' : 'border-slate-200 text-slate-600 hover:bg-slate-50'"
                                   class="border rounded-xl p-2.5 flex flex-col items-center justify-center text-center cursor-pointer transition-colors">
                                <input type="radio" value="out" x-model="adjustForm.adjustment_type" class="sr-only"/>
                                <span class="text-xs font-semibold">Stok Keluar (-)</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Rusak / Hilang</span>
                            </label>

                            <label :class="adjustForm.adjustment_type === 'adjustment' ? 'border-blue-500 bg-blue-50/50 text-blue-800' : 'border-slate-200 text-slate-600 hover:bg-slate-50'"
                                   class="border rounded-xl p-2.5 flex flex-col items-center justify-center text-center cursor-pointer transition-colors">
                                <input type="radio" value="adjustment" x-model="adjustForm.adjustment_type" class="sr-only"/>
                                <span class="text-xs font-semibold">Opname Fisik (=)</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Set Stok Nyata</span>
                            </label>
                        </div>
                    </div>

                    <!-- Jumlah Quantity -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            <span x-show="adjustForm.adjustment_type === 'in'">Jumlah Masuk (Kuantitas)</span>
                            <span x-show="adjustForm.adjustment_type === 'out'">Jumlah Keluar (Kuantitas)</span>
                            <span x-show="adjustForm.adjustment_type === 'adjustment'">Stok Nyata Hasil Opname</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input x-model.number="adjustForm.quantity" 
                               required 
                               type="number" 
                               min="1" 
                               placeholder="Contoh: 10" 
                               class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                    </div>

                    <!-- Live Calculation Preview -->
                    <template x-if="selectedVariantForAdjust">
                        <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200 text-xs space-y-1.5">
                            <div class="flex justify-between text-slate-500">
                                <span>Stok Saat Ini:</span>
                                <strong class="text-slate-800 font-semibold" x-text="selectedVariantForAdjust.stock_quantity + ' unit'"></strong>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Perubahan Kuantitas:</span>
                                <strong :class="{
                                    'text-emerald-600': adjustForm.adjustment_type === 'in',
                                    'text-rose-600': adjustForm.adjustment_type === 'out',
                                    'text-blue-600': adjustForm.adjustment_type === 'adjustment'
                                }" x-text="calculateDeltaText()"></strong>
                            </div>
                            <div class="flex justify-between pt-1.5 border-t border-slate-200 font-semibold">
                                <span class="text-slate-700">Estimasi Stok Akhir:</span>
                                <span :class="calculateNewStock() < 0 ? 'text-rose-600 font-bold' : 'text-slate-900 font-bold'" 
                                      x-text="calculateNewStock() + ' unit'"></span>
                            </div>
                            <div x-show="calculateNewStock() < 0" class="text-rose-600 text-[11px] font-medium pt-1">
                                Peringatan: Stok tidak mencukupi untuk pengurangan ini!
                            </div>
                        </div>
                    </template>

                    <!-- Catatan / Note -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Alasan Mutasi</label>
                        <input x-model="adjustForm.note" 
                               type="text" 
                               placeholder="Contoh: Pembelian supplier, barang kadaluarsa, opname akhir bulan..." 
                               class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button @click="modalAdjustOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button :disabled="submitting || calculateNewStock() < 0" type="submit" class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition-all flex items-center gap-2">
                            <span x-show="!submitting">Simpan Mutasi Stok</span>
                            <span x-show="submitting" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL: RIWAYAT MUTASI STOK ==================== -->
    <div x-show="modalHistoryOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalHistoryOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalHistoryOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="modalHistoryOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-4xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Audit Trail Mutasi Stok</h3>
                        <p class="text-xs text-slate-500 mt-0.5" x-text="historyVariantFilter ? 'Riwayat pergerakan stok untuk ' + historyVariantFilter.full_name : 'Semua catatan keluar, masuk, dan penyesuaian inventaris'"></p>
                    </div>
                    <button @click="modalHistoryOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="mt-4">
                    <!-- Filter Pill inside modal -->
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-600">Tipe Mutasi:</span>
                            <select x-model="historyMovementType" 
                                    @change="fetchMovements(1)" 
                                    class="text-xs border-slate-200 rounded-lg px-2.5 py-1.5 focus:ring-1 focus:ring-blue-500">
                                <option value="">Semua Pergerakan</option>
                                <option value="in">Barang Masuk (In)</option>
                                <option value="out">Barang Keluar (Out)</option>
                                <option value="adjustment">Penyesuaian (Adjustment)</option>
                            </select>
                        </div>
                        <template x-if="historyVariantFilter">
                            <button @click="clearHistoryVariantFilter()" class="text-xs text-blue-600 hover:underline">
                                Tampilkan Semua Produk
                            </button>
                        </template>
                    </div>

                    <!-- Movement Table -->
                    <div class="border border-slate-200 rounded-xl overflow-hidden max-h-[420px] overflow-y-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-50 sticky top-0 border-b border-slate-200 font-semibold text-slate-600">
                                <tr>
                                    <th class="py-2.5 px-3">Waktu</th>
                                    <th class="py-2.5 px-3">No. Dokumen</th>
                                    <th class="py-2.5 px-3">Produk &amp; Varian</th>
                                    <th class="py-2.5 px-3">Tipe</th>
                                    <th class="py-2.5 px-3 text-right">Perubahan</th>
                                    <th class="py-2.5 px-3 text-right">Stok Akhir</th>
                                    <th class="py-2.5 px-3">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="mov in movements" :key="mov.id">
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-2.5 px-3 text-slate-500 whitespace-nowrap" x-text="formatDate(mov.created_at)"></td>
                                        <td class="py-2.5 px-3 font-mono font-medium text-slate-700" x-text="mov.adjustment_number || '-'"></td>
                                        <td class="py-2.5 px-3">
                                            <span class="font-medium text-slate-900" x-text="mov.variant?.product?.name || '-'"></span>
                                            <span class="text-slate-400" x-text="' (' + (mov.variant?.variant_name || '-') + ')'"></span>
                                        </td>
                                        <td class="py-2.5 px-3">
                                            <span :class="{
                                                'bg-emerald-50 text-emerald-700 border-emerald-200': mov.movement_type === 'in',
                                                'bg-rose-50 text-rose-700 border-rose-200': mov.movement_type === 'out',
                                                'bg-blue-50 text-blue-700 border-blue-200': mov.movement_type === 'adjustment'
                                            }" class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase border" 
                                               x-text="mov.movement_type">
                                            </span>
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-bold" 
                                            :class="mov.quantity_delta > 0 ? 'text-emerald-600' : 'text-rose-600'" 
                                            x-text="(mov.quantity_delta > 0 ? '+' : '') + mov.quantity_delta">
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-semibold text-slate-800" x-text="mov.quantity_after"></td>
                                        <td class="py-2.5 px-3 text-slate-500 max-w-xs truncate" :title="mov.note" x-text="mov.note || '-'"></td>
                                    </tr>
                                </template>

                                <tr x-show="movements.length === 0">
                                    <td colspan="7" class="py-8 text-center text-slate-400">
                                        Belum ada riwayat mutasi stok untuk filter ini.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-slate-100 mt-4">
                    <button @click="modalHistoryOpen = false" type="button" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold hover:bg-slate-200 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function inventoryManagement() {
        return {
            items: [],
            allVariantOptions: [],
            categories: [],
            summary: {
                total_sku: 0,
                safe_stock_count: 0,
                low_stock_count: 0,
                out_of_stock_count: 0,
                total_asset_value: 0,
                total_asset_value_formatted: 'Rp 0'
            },
            loading: false,
            submitting: false,
            searchQuery: '',
            statusFilter: '',
            selectedCategory: '',
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                from: 0,
                to: 0,
            },

            // Modals
            modalAdjustOpen: false,
            modalHistoryOpen: false,
            selectedVariantForAdjust: null,
            adjustForm: {
                variant_id: '',
                adjustment_type: 'in',
                quantity: 10,
                note: ''
            },

            // History Modal
            historyVariantFilter: null,
            historyMovementType: '',
            movements: [],

            async init() {
                await Promise.all([
                    this.fetchSummary(),
                    this.fetchCategories(),
                    this.fetchInventory(1)
                ]);
            },

            async fetchSummary() {
                try {
                    const res = await fetch('/api/v1/master-data/stok/summary');
                    const json = await res.json();
                    if (json.success) {
                        this.summary = json.data;
                    }
                } catch (e) {
                    console.error('Failed to load summary', e);
                }
            },

            async fetchCategories() {
                try {
                    const res = await fetch('/api/v1/master-data/produk/categories');
                    const json = await res.json();
                    if (json.success) {
                        this.categories = json.data;
                    }
                } catch (e) {
                    console.error('Failed to load categories', e);
                }
            },

            async fetchInventory(page = 1) {
                this.loading = true;
                try {
                    const params = new URLSearchParams({
                        page: page,
                        per_page: 8
                    });

                    if (this.searchQuery.trim()) {
                        params.append('search', this.searchQuery.trim());
                    }
                    if (this.statusFilter) {
                        params.append('stock_status', this.statusFilter);
                    }
                    if (this.selectedCategory) {
                        params.append('category_id', this.selectedCategory);
                    }

                    const res = await fetch(`/api/v1/master-data/stok?${params.toString()}`);
                    const json = await res.json();

                    if (json.success) {
                        this.items = json.data;
                        this.pagination = {
                            current_page: json.meta?.current_page || 1,
                            last_page: json.meta?.last_page || 1,
                            total: json.meta?.total || 0,
                            from: json.meta?.from || 0,
                            to: json.meta?.to || 0,
                        };

                        // Populate dropdown options if empty
                        if (this.allVariantOptions.length === 0 && this.items.length > 0) {
                            this.allVariantOptions = [...this.items];
                        }
                    } else {
                        window.showToast(json.message || 'Gagal memuat inventaris', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Gagal terhubung ke server', 'error');
                } finally {
                    this.loading = false;
                }
            },

            openAdjustModal(variant = null) {
                // Ensure dropdown options have items
                if (this.allVariantOptions.length === 0 && this.items.length > 0) {
                    this.allVariantOptions = [...this.items];
                }

                if (variant) {
                    this.adjustForm.variant_id = variant.id;
                    this.selectedVariantForAdjust = variant;
                } else if (this.allVariantOptions.length > 0) {
                    this.adjustForm.variant_id = this.allVariantOptions[0].id;
                    this.selectedVariantForAdjust = this.allVariantOptions[0];
                } else {
                    this.adjustForm.variant_id = '';
                    this.selectedVariantForAdjust = null;
                }

                this.adjustForm.adjustment_type = 'in';
                this.adjustForm.quantity = 10;
                this.adjustForm.note = '';
                this.modalAdjustOpen = true;
            },

            onVariantSelected() {
                const found = this.allVariantOptions.find(v => v.id === this.adjustForm.variant_id) ||
                              this.items.find(v => v.id === this.adjustForm.variant_id);
                this.selectedVariantForAdjust = found || null;
            },

            calculateDeltaText() {
                const qty = parseInt(this.adjustForm.quantity) || 0;
                if (this.adjustForm.adjustment_type === 'in') return `+${qty} unit`;
                if (this.adjustForm.adjustment_type === 'out') return `-${qty} unit`;
                if (!this.selectedVariantForAdjust) return `${qty} unit`;
                const delta = qty - this.selectedVariantForAdjust.stock_quantity;
                return (delta >= 0 ? `+${delta}` : `${delta}`) + ` unit (Koreksi Opname)`;
            },

            calculateNewStock() {
                if (!this.selectedVariantForAdjust) return 0;
                const oldStock = parseInt(this.selectedVariantForAdjust.stock_quantity) || 0;
                const qty = parseInt(this.adjustForm.quantity) || 0;

                if (this.adjustForm.adjustment_type === 'in') return oldStock + qty;
                if (this.adjustForm.adjustment_type === 'out') return oldStock - qty;
                return qty;
            },

            async submitAdjustment() {
                if (this.calculateNewStock() < 0) {
                    window.showToast('Stok tidak boleh bernilai negatif', 'error');
                    return;
                }

                this.submitting = true;
                try {
                    const payload = {
                        variant_id: this.adjustForm.variant_id,
                        adjustment_type: this.adjustForm.adjustment_type,
                        quantity: parseInt(this.adjustForm.quantity) || 0,
                        note: this.adjustForm.note || 'Penyesuaian stok oleh admin'
                    };

                    const res = await fetch('/api/v1/master-data/stok/adjust', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify(payload)
                    });

                    const json = await res.json();
                    if (json.success) {
                        this.modalAdjustOpen = false;
                        window.showToast('Penyesuaian stok berhasil disimpan!', 'success');
                        await Promise.all([
                            this.fetchSummary(),
                            this.fetchInventory(this.pagination.current_page)
                        ]);
                    } else {
                        window.showToast(json.message || 'Gagal menyimpan penyesuaian stok', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Terjadi kesalahan saat menyesuaikan stok', 'error');
                } finally {
                    this.submitting = false;
                }
            },

            async openHistoryModal(variant = null) {
                this.historyVariantFilter = variant;
                this.historyMovementType = '';
                this.modalHistoryOpen = true;
                await this.fetchMovements(1);
            },

            clearHistoryVariantFilter() {
                this.historyVariantFilter = null;
                this.fetchMovements(1);
            },

            async fetchMovements(page = 1) {
                try {
                    const params = new URLSearchParams({
                        page: page,
                        per_page: 25
                    });

                    if (this.historyVariantFilter?.id) {
                        params.append('variant_id', this.historyVariantFilter.id);
                    }
                    if (this.historyMovementType) {
                        params.append('movement_type', this.historyMovementType);
                    }

                    const res = await fetch(`/api/v1/master-data/stok/movements?${params.toString()}`);
                    const json = await res.json();
                    if (json.success) {
                        this.movements = json.data;
                    }
                } catch (e) {
                    console.error('Failed to load movements', e);
                }
            },

            formatDate(isoString) {
                if (!isoString) return '-';
                const d = new Date(isoString);
                return d.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        };
    }
</script>
@endpush
