@extends('layouts.admin')

@section('title', 'UMKMGo - Kelola Produk')

@section('content')
<main class="p-8 flex-1 flex flex-col justify-between" 
      data-purpose="product-list-page" 
      x-data="productManagement()" 
      x-init="init()">
    <div>
        <!-- Page Title & Add Button Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Produk</h1>
                <p class="text-sm text-slate-500 mt-1 font-normal">
                    Total <span class="font-semibold text-slate-700" x-text="pagination.total">0</span> Produk Terdaftar
                </p>
            </div>
            <button @click="openCreateModal()" 
                    class="bg-[#2563EB] hover:bg-[#1d4ed8] text-white px-5 py-2.5 rounded-xl font-medium text-sm flex items-center justify-center gap-2 shadow-sm transition-all" 
                    type="button">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                <span>Tambah Produk</span>
            </button>
        </div>

        <!-- Filter and Search Row -->
        <div class="grid grid-cols-12 gap-4 mb-6">
            <!-- Search Bar -->
            <div class="col-span-12 lg:col-span-6 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
                <input x-model.debounce.350ms="searchQuery" 
                       @input="fetchProducts(1)"
                       class="w-full bg-white border border-slate-200/90 rounded-xl pl-11 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm" 
                       placeholder="Cari Nama Produk, SKU, atau Deskripsi..." 
                       type="text"/>
            </div>

            <!-- Kategori Filter -->
            <div class="col-span-6 lg:col-span-3 relative">
                <select x-model="selectedCategory" 
                        @change="fetchProducts(1)"
                        class="w-full bg-white border border-slate-200/90 rounded-xl px-4 py-2.5 text-sm text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer shadow-sm pr-10">
                    <option value="">Semua Kategori</option>
                    <template x-for="cat in categories" :key="cat.id">
                        <option :value="cat.id" x-text="cat.name"></option>
                    </template>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </div>
            </div>

            <!-- Sort Filter -->
            <div class="col-span-6 lg:col-span-3 relative">
                <select x-model="sortBy" 
                        @change="fetchProducts(1)"
                        class="w-full bg-white border border-slate-200/90 rounded-xl px-4 py-2.5 text-sm text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer shadow-sm pr-10">
                    <option value="latest">Urutan: Terbaru</option>
                    <option value="oldest">Urutan: Terlama</option>
                    <option value="name_asc">Nama: A - Z</option>
                    <option value="name_desc">Nama: Z - A</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden relative min-h-[300px]">
            <!-- Loading Overlay -->
            <div x-show="loading" 
                 x-transition
                 class="absolute inset-0 bg-white/70 backdrop-blur-xs flex items-center justify-center z-10">
                <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-white border border-slate-200 shadow-md">
                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium text-slate-700">Memuat data produk...</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" data-purpose="product-table">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-[12.5px] font-semibold text-slate-600 bg-white">
                            <th class="py-4 px-6 font-semibold" scope="col">Nama Produk</th>
                            <th class="py-4 px-6 font-semibold" scope="col">Kategori</th>
                            <th class="py-4 px-6 font-semibold" scope="col">Harga Jual</th>
                            <th class="py-4 px-6 font-semibold" scope="col">Stok</th>
                            <th class="py-4 px-6 font-semibold" scope="col">Status</th>
                            <th class="py-4 px-6 font-semibold text-right" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <!-- Dynamic Product Rows -->
                        <template x-for="product in products" :key="product.id">
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <img :src="product.thumbnail" 
                                             :alt="product.name" 
                                             class="w-11 h-11 rounded-xl object-cover border border-slate-200/60 shadow-sm flex-shrink-0 bg-slate-100"
                                             onerror="this.src='/images/dashboard/coffee-americano.jpg'"/>
                                        <div>
                                            <div class="font-medium text-slate-900 text-sm" x-text="product.name"></div>
                                            <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-1.5">
                                                <span x-text="product.variants?.[0]?.sku || 'SKU Auto'"></span>
                                                <span class="text-slate-300">•</span>
                                                <span x-text="(product.variants_count || 1) + ' Varian'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-[#EEF2F6] text-slate-600"
                                          x-text="product.category?.name || 'Umum'">
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 font-semibold text-slate-800" x-text="product.price_formatted"></td>
                                <td class="py-3.5 px-6 text-sm font-medium text-slate-800" x-text="(product.total_stock || 0) + ' unit'"></td>
                                <td class="py-3.5 px-6">
                                    <span :class="product.status === 'active' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-slate-100 text-slate-600 border-slate-200'"
                                          class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium border capitalize"
                                          x-text="product.status === 'active' ? 'Aktif' : 'Draft'">
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditModal(product)" 
                                                class="text-slate-400 hover:text-blue-600 p-1.5 rounded-lg hover:bg-blue-50 transition-colors" 
                                                title="Edit Produk" 
                                                type="button">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                        <button @click="openDeleteModal(product)" 
                                                class="text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors" 
                                                title="Hapus Produk" 
                                                type="button">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Empty State -->
                        <tr x-show="!loading && products.length === 0">
                            <td colspan="6" class="py-12 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-800">Tidak ada produk ditemukan</h3>
                                    <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau tambahkan produk baru.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- BEGIN: PaginationFooter -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 text-sm text-slate-500" data-purpose="pagination-container">
        <div>
            Menampilkan <span class="font-medium text-slate-700" x-text="pagination.from || 0"></span> - 
            <span class="font-medium text-slate-700" x-text="pagination.to || 0"></span> dari 
            <span class="font-medium text-slate-700" x-text="pagination.total || 0"></span> produk
        </div>
        <div class="flex items-center gap-1.5">
            <!-- Prev Button -->
            <button @click="fetchProducts(pagination.current_page - 1)" 
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
                <button @click="fetchProducts(p)"
                        x-show="p === 1 || p === pagination.last_page || Math.abs(p - pagination.current_page) <= 1"
                        :class="p === pagination.current_page ? 'bg-[#2563EB] text-white shadow-sm font-medium' : 'hover:bg-slate-100 text-slate-600 font-normal'"
                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors text-sm" 
                        type="button" 
                        x-text="p">
                </button>
            </template>

            <!-- Next Button -->
            <button @click="fetchProducts(pagination.current_page + 1)" 
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

    <!-- ==================== MODAL: TAMBAH PRODUK ==================== -->
    <div x-show="modalCreateOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalCreateOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalCreateOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="modalCreateOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="modal-title">Tambah Produk Baru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Isi detail informasi produk dan stok awal.</p>
                    </div>
                    <button @click="modalCreateOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="submitCreateProduct()" class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Produk -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Produk <span class="text-rose-500">*</span></label>
                            <input x-model="createForm.name" required type="text" placeholder="Contoh: Coffee Americano, Kemeja Flannel..." class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                            <select x-model="createForm.category_id" required class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Kategori</option>
                                <template x-for="cat in categories" :key="cat.id">
                                    <option :value="cat.id" x-text="cat.name"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Kondisi -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kondisi</label>
                            <select x-model="createForm.condition" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="baru">Baru</option>
                                <option value="bekas">Bekas</option>
                            </select>
                        </div>

                        <!-- Berat -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Berat (Gram)</label>
                            <input x-model.number="createForm.weight_gram" type="number" min="0" placeholder="250" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Status Publikasi</label>
                            <select x-model="createForm.status" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="active">Aktif (Langsung Dijual)</option>
                                <option value="draft">Draft (Simpan Sementara)</option>
                            </select>
                        </div>

                        <!-- Deskripsi -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                            <textarea x-model="createForm.description" rows="2" placeholder="Catatan atau keterangan produk..." class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                    </div>

                    <!-- Varian & Harga Box -->
                    <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 mt-2">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Informasi Harga & Stok Awal</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Jual (Rp) <span class="text-rose-500">*</span></label>
                                <input x-model.number="createForm.variant.price" required type="number" min="0" placeholder="15000" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Stok Awal <span class="text-rose-500">*</span></label>
                                <input x-model.number="createForm.variant.stock_quantity" required type="number" min="0" placeholder="50" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Batas Stok Menipis</label>
                                <input x-model.number="createForm.variant.low_stock_threshold" type="number" min="0" placeholder="5" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Varian</label>
                                <input x-model="createForm.variant.variant_name" type="text" placeholder="Standar (Default)" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">SKU (Opsional)</label>
                                <input x-model="createForm.variant.sku" type="text" placeholder="Auto-generate jika kosong" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button @click="modalCreateOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button :disabled="submitting" type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition-all flex items-center gap-2">
                            <span x-show="!submitting">Simpan Produk</span>
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

    <!-- ==================== MODAL: EDIT PRODUK ==================== -->
    <div x-show="modalEditOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalEditOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalEditOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="modalEditOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Edit Data Produk</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui rincian produk, harga, dan pengaturan.</p>
                    </div>
                    <button @click="modalEditOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="submitEditProduct()" class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Produk -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Produk <span class="text-rose-500">*</span></label>
                            <input x-model="editForm.name" required type="text" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                            <select x-model="editForm.category_id" required class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <template x-for="cat in categories" :key="cat.id">
                                    <option :value="cat.id" x-text="cat.name"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                            <select x-model="editForm.status" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="active">Aktif</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <!-- Kondisi -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kondisi</label>
                            <select x-model="editForm.condition" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="baru">Baru</option>
                                <option value="bekas">Bekas</option>
                            </select>
                        </div>

                        <!-- Berat -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Berat (Gram)</label>
                            <input x-model.number="editForm.weight_gram" type="number" min="0" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                        </div>

                        <!-- Deskripsi -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                            <textarea x-model="editForm.description" rows="2" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                    </div>

                    <!-- Varian Edit Box -->
                    <template x-if="editForm.variant">
                        <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 mt-2">
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Varian & Harga</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Jual (Rp)</label>
                                    <input x-model.number="editForm.variant.price" required type="number" min="0" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Varian</label>
                                    <input x-model="editForm.variant.variant_name" type="text" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Batas Menipis</label>
                                    <input x-model.number="editForm.variant.low_stock_threshold" type="number" min="0" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-2 italic">* Catatan: Untuk merestok atau mengubah kuantitas stok saat ini, gunakan menu <strong>Inventory / Inventaris</strong>.</p>
                        </div>
                    </template>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button @click="modalEditOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button :disabled="submitting" type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition-all flex items-center gap-2">
                            <span x-show="!submitting">Perbarui Produk</span>
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

    <!-- ==================== MODAL: KONFIRMASI HAPUS ==================== -->
    <div x-show="modalDeleteOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalDeleteOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalDeleteOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="modalDeleteOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>

                <h3 class="text-base font-bold text-slate-900">Arsipkan Produk Ini?</h3>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Apakah Anda yakin ingin mengarsipkan produk <strong class="text-slate-800" x-text="productToDelete?.name"></strong>? Produk tidak akan tampil di katalog aktif tetapi riwayat transaksi tetap terjaga.
                </p>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button @click="modalDeleteOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button @click="submitDeleteProduct()" :disabled="submitting" type="button" class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition-all flex items-center gap-2">
                        <span x-show="!submitting">Ya, Arsipkan</span>
                        <span x-show="submitting">Mengarsipkan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function productManagement() {
        return {
            products: [],
            categories: [],
            loading: false,
            submitting: false,
            searchQuery: '',
            selectedCategory: '',
            sortBy: 'latest',
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                from: 0,
                to: 0,
            },

            // Modals state
            modalCreateOpen: false,
            modalEditOpen: false,
            modalDeleteOpen: false,
            productToDelete: null,

            // Forms
            createForm: {
                name: '',
                category_id: '',
                condition: 'baru',
                status: 'active',
                weight_gram: 100,
                description: '',
                variant: {
                    variant_name: 'Standar',
                    sku: '',
                    price: '',
                    stock_quantity: 10,
                    low_stock_threshold: 5
                }
            },
            editForm: {
                id: '',
                name: '',
                category_id: '',
                condition: 'baru',
                status: 'active',
                weight_gram: 0,
                description: '',
                variant: null
            },

            async init() {
                await this.fetchCategories();
                await this.fetchProducts(1);
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

            async fetchProducts(page = 1) {
                this.loading = true;
                try {
                    const params = new URLSearchParams({
                        page: page,
                        per_page: 8,
                        sort_by: this.sortBy,
                    });

                    if (this.searchQuery.trim()) {
                        params.append('search', this.searchQuery.trim());
                    }
                    if (this.selectedCategory) {
                        params.append('category_id', this.selectedCategory);
                    }

                    const res = await fetch(`/api/v1/master-data/produk?${params.toString()}`);
                    const json = await res.json();

                    if (json.success) {
                        this.products = json.data;
                        this.pagination = {
                            current_page: json.meta?.current_page || 1,
                            last_page: json.meta?.last_page || 1,
                            total: json.meta?.total || 0,
                            from: json.meta?.from || 0,
                            to: json.meta?.to || 0,
                        };
                    } else {
                        window.showToast(json.message || 'Gagal memuat produk', 'error');
                    }
                } catch (e) {
                    console.error('Fetch error:', e);
                    window.showToast('Gagal terhubung ke server', 'error');
                } finally {
                    this.loading = false;
                }
            },

            openCreateModal() {
                this.createForm = {
                    name: '',
                    category_id: this.categories.length > 0 ? this.categories[0].id : '',
                    condition: 'baru',
                    status: 'active',
                    weight_gram: 200,
                    description: '',
                    variant: {
                        variant_name: 'Standar',
                        sku: '',
                        price: '',
                        stock_quantity: 10,
                        low_stock_threshold: 5
                    }
                };
                this.modalCreateOpen = true;
            },

            async submitCreateProduct() {
                this.submitting = true;
                try {
                    const payload = {
                        name: this.createForm.name,
                        category_id: this.createForm.category_id,
                        condition: this.createForm.condition,
                        status: this.createForm.status,
                        weight_gram: this.createForm.weight_gram,
                        description: this.createForm.description,
                        variants: [
                            {
                                variant_name: this.createForm.variant.variant_name || 'Standar',
                                sku: this.createForm.variant.sku || null,
                                price: parseFloat(this.createForm.variant.price),
                                stock_quantity: parseInt(this.createForm.variant.stock_quantity) || 0,
                                low_stock_threshold: parseInt(this.createForm.variant.low_stock_threshold) || 5
                            }
                        ]
                    };

                    const res = await fetch('/api/v1/master-data/produk', {
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
                        this.modalCreateOpen = false;
                        window.showToast('Produk berhasil ditambahkan!', 'success');
                        await this.fetchProducts(1);
                    } else {
                        window.showToast(json.message || 'Gagal menambahkan produk', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Terjadi kesalahan sistem saat menyimpan produk', 'error');
                } finally {
                    this.submitting = false;
                }
            },

            openEditModal(product) {
                const firstVar = product.variants?.[0] || null;
                this.editForm = {
                    id: product.id,
                    name: product.name,
                    category_id: product.category?.id || '',
                    condition: product.condition || 'baru',
                    status: product.status || 'active',
                    weight_gram: product.weight_gram || 0,
                    description: product.description || '',
                    variant: firstVar ? {
                        id: firstVar.id,
                        variant_name: firstVar.variant_name,
                        price: firstVar.price,
                        low_stock_threshold: firstVar.low_stock_threshold
                    } : null
                };
                this.modalEditOpen = true;
            },

            async submitEditProduct() {
                this.submitting = true;
                try {
                    const payload = {
                        name: this.editForm.name,
                        category_id: this.editForm.category_id,
                        condition: this.editForm.condition,
                        status: this.editForm.status,
                        weight_gram: this.editForm.weight_gram,
                        description: this.editForm.description,
                    };

                    if (this.editForm.variant) {
                        payload.variants = [
                            {
                                id: this.editForm.variant.id,
                                variant_name: this.editForm.variant.variant_name,
                                price: parseFloat(this.editForm.variant.price),
                                low_stock_threshold: parseInt(this.editForm.variant.low_stock_threshold)
                            }
                        ];
                    }

                    const res = await fetch(`/api/v1/master-data/produk/${this.editForm.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify(payload)
                    });

                    const json = await res.json();
                    if (json.success) {
                        this.modalEditOpen = false;
                        window.showToast('Produk berhasil diperbarui!', 'success');
                        await this.fetchProducts(this.pagination.current_page);
                    } else {
                        window.showToast(json.message || 'Gagal memperbarui produk', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Terjadi kesalahan sistem', 'error');
                } finally {
                    this.submitting = false;
                }
            },

            openDeleteModal(product) {
                this.productToDelete = product;
                this.modalDeleteOpen = true;
            },

            async submitDeleteProduct() {
                if (!this.productToDelete) return;
                this.submitting = true;
                try {
                    const res = await fetch(`/api/v1/master-data/produk/${this.productToDelete.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });

                    const json = await res.json();
                    if (json.success) {
                        this.modalDeleteOpen = false;
                        this.productToDelete = null;
                        window.showToast('Produk berhasil diarsipkan', 'success');
                        await this.fetchProducts(this.pagination.current_page);
                    } else {
                        window.showToast(json.message || 'Gagal menghapus produk', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Terjadi kesalahan saat menghapus', 'error');
                } finally {
                    this.submitting = false;
                }
            }
        };
    }
</script>
@endpush
