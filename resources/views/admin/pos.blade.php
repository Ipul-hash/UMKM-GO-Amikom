@extends('layouts.admin')

@section('title', 'UMKMGo - POS / Kasir')

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden !important;
        }
        #printable-thermal-receipt, #printable-thermal-receipt * {
            visibility: visible !important;
        }
        #printable-thermal-receipt {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 80mm !important;
            margin: 0 !important;
            padding: 10px !important;
            box-shadow: none !important;
            border: none !important;
            background: white !important;
            color: black !important;
        }
    }
</style>
@endpush

@section('content')
<div class="flex-1 flex overflow-hidden" 
     x-data="posCashier()" 
     x-init="init()"
     data-purpose="pos-cashier-screen">

    <!-- BEGIN: CatalogArea (Left Column) -->
    <main class="flex-1 flex flex-col overflow-y-auto px-8 py-6 bg-white border-r border-slate-100 min-w-0 relative">
        <!-- Header POS Title & Active Status -->
        <div class="flex items-start justify-between mb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">POS / Kasir</h1>
                <p class="text-xs text-slate-500 mt-1">Pilih produk dan mulai transaksi kasir pelanggan Anda.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span x-text="activeShiftInfo?.register_name || 'Kasir Aktif'"></span>
                </span>
            </div>
        </div>

        <!-- Search Bar and Filter Controls -->
        <div class="flex items-center gap-3 mb-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </div>
                <input x-ref="searchInput"
                       x-model.debounce.300ms="searchQuery"
                       @input="filterProducts()"
                       class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-xs" 
                       placeholder="Cari Nama Produk, SKU, atau tekan [/]..." 
                       type="text"/>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <kbd class="px-1.5 py-0.5 text-[10px] font-medium text-slate-400 bg-white border border-slate-200 rounded shadow-xs">/</kbd>
                </div>
            </div>
        </div>

        <!-- Category Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-4 no-scrollbar">
            <button @click="setCategory('all')" 
                    :class="selectedCategory === 'all' ? 'bg-blue-600 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70 font-medium'"
                    class="px-4 py-1.5 rounded-full text-xs transition-colors whitespace-nowrap">
                Semua
            </button>
            <template x-for="cat in categories" :key="cat.id">
                <button @click="setCategory(cat.slug || cat.name)" 
                        :class="selectedCategory === (cat.slug || cat.name) ? 'bg-blue-600 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70 font-medium'"
                        class="px-4 py-1.5 rounded-full text-xs transition-colors whitespace-nowrap"
                        x-text="cat.name">
                </button>
            </template>
        </div>

        <!-- Section Products Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Katalog Produk</h2>
            <span class="text-[11px] text-slate-400" x-text="filteredProducts.length + ' produk tersedia'"></span>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="py-16 flex flex-col items-center justify-center text-slate-400">
            <svg class="animate-spin h-8 w-8 text-blue-600 mb-3" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-xs font-medium">Memuat katalog produk POS...</span>
        </div>

        <!-- Product Cards Grid (3 Columns) -->
        <div x-show="!loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pb-8">
            <template x-for="product in filteredProducts" :key="product.id">
                <div @click="addToCart(product)"
                     :class="product.stock_quantity <= 0 ? 'opacity-60 cursor-not-allowed bg-slate-50' : 'cursor-pointer hover:border-blue-400 hover:shadow-md bg-white'"
                     class="border border-slate-200/80 rounded-2xl p-3 shadow-xs transition-all flex flex-col justify-between group relative select-none">
                    <div>
                        <div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
                            <img :src="product.thumbnail" 
                                 :alt="product.name" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                 onerror="this.src='/images/dashboard/coffee-americano.jpg'"/>
                            
                            <!-- In-Cart Counter Badge -->
                            <template x-if="getItemQtyInCart(product) > 0">
                                <span class="absolute top-2 right-2 px-2 py-0.5 bg-blue-600 text-white text-[10px] font-bold rounded-full shadow-md animate-pulse" 
                                      x-text="getItemQtyInCart(product) + ' di keranjang'"></span>
                            </template>

                            <!-- Out of Stock Badge -->
                            <template x-if="product.stock_quantity <= 0">
                                <span class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center text-white text-xs font-bold uppercase tracking-wider">
                                    Stok Habis
                                </span>
                            </template>
                        </div>
                        <h3 class="font-bold text-xs text-slate-900 leading-tight line-clamp-1" x-text="product.name"></h3>
                        <p class="text-blue-600 font-bold text-xs mt-1" x-text="product.price_formatted"></p>
                    </div>

                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100 text-[11px]">
                        <span class="text-slate-400 font-normal" x-text="'Stok: ' + product.stock_quantity + ' unit'"></span>
                        <button :disabled="product.stock_quantity <= 0" 
                                class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center group-hover:bg-blue-700 transition-colors shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty Filter Results -->
        <div x-show="!loading && filteredProducts.length === 0" class="py-16 text-center">
            <p class="text-sm font-semibold text-slate-700">Tidak ada produk yang cocok</p>
            <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian atau kategori lain.</p>
        </div>
    </main>
    <!-- END: CatalogArea -->

    <!-- BEGIN: CartPanel (Right Column) -->
    <aside class="w-96 flex-shrink-0 flex flex-col justify-between bg-white px-6 py-6 border-l border-slate-200/80 shadow-xs z-10">
        <!-- Cart Top Elements -->
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Cart Header -->
            <div class="flex items-center justify-between pb-1">
                <h2 class="text-base font-bold text-slate-900">Keranjang Kasir</h2>
                <span class="text-xs font-semibold px-2.5 py-0.5 bg-blue-50 text-blue-600 rounded-md" 
                      x-text="totalCartItemsCount + ' item'"></span>
            </div>
            <p class="text-xs text-slate-400 mb-3" x-text="'Transaksi Kasir #' + currentTrxCode"></p>

            <!-- Customer Selector -->
            <div class="mb-4">
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Pelanggan:</label>
                <select x-model="selectedCustomer" 
                        class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <template x-for="c in customerOptions" :key="c.id || 'general'">
                        <option :value="c.id || ''" x-text="c.full_name + (c.phone !== '-' ? ' (' + c.phone + ')' : '')"></option>
                    </template>
                </select>
            </div>

            <!-- Cart Items List (Scrollable) -->
            <div class="flex-1 overflow-y-auto pr-1 space-y-3">
                <template x-for="(item, index) in cart" :key="item.variant_id">
                    <div class="space-y-1.5 pb-2.5 border-b border-slate-100">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h4 class="font-bold text-xs text-slate-900 leading-tight truncate" x-text="item.name"></h4>
                                <p class="text-[11px] text-slate-400 mt-0.5" x-text="formatRupiah(item.price) + ' / unit'"></p>
                            </div>
                            <button @click="removeFromCart(index)" 
                                    class="text-slate-400 hover:text-rose-500 transition-colors p-1" 
                                    title="Hapus dari keranjang">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-white shadow-2xs">
                                <button @click="decrementQty(index)" 
                                        class="w-6 h-6 flex items-center justify-center text-slate-500 hover:bg-slate-100 text-xs font-bold transition-colors">-</button>
                                <span class="w-8 text-center text-xs font-semibold text-slate-800" x-text="item.quantity"></span>
                                <button @click="incrementQty(index)" 
                                        class="w-6 h-6 flex items-center justify-center text-blue-600 hover:bg-slate-100 text-xs font-bold transition-colors">+</button>
                            </div>
                            <span class="font-bold text-xs text-slate-900" x-text="formatRupiah(item.price * item.quantity)"></span>
                        </div>
                    </div>
                </template>

                <!-- Empty Cart State -->
                <div x-show="cart.length === 0" class="py-12 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-700">Keranjang Masih Kosong</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Klik produk pada katalog untuk menambahkan ke pesanan.</p>
                </div>
            </div>

            <!-- Transaction Note -->
            <div class="pt-2 border-t border-slate-100">
                <input x-model="transactionNote" 
                       type="text" 
                       placeholder="Catatan pesanan kasir (opsional)..." 
                       class="w-full text-xs border border-slate-200 rounded-lg px-3 py-1.5 focus:ring-1 focus:ring-blue-500"/>
            </div>
        </div>

        <!-- Cart Summary & Checkout Action Section -->
        <div class="pt-3 border-t border-slate-100 space-y-3">
            <!-- Summary Details -->
            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>Subtotal (<span x-text="totalCartItemsCount"></span> item)</span>
                    <span class="text-slate-800 font-medium" x-text="formatRupiah(calculateSubtotal())"></span>
                </div>
                <div class="flex items-center justify-between text-slate-500">
                    <span>Diskon (Rp)</span>
                    <input x-model.number="discountAmount" 
                           type="number" 
                           min="0" 
                           placeholder="0" 
                           class="w-24 text-right text-xs py-0.5 px-2 border border-slate-200 rounded-md focus:ring-1 focus:ring-blue-500"/>
                </div>
            </div>

            <!-- Total Calculation -->
            <div class="flex items-baseline justify-between pt-1 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-900">Total Akhir</span>
                <span class="text-xl font-bold text-blue-600" x-text="formatRupiah(calculateGrandTotal())"></span>
            </div>

            <!-- Checkout & Cancel Buttons -->
            <div class="space-y-2">
                <button @click="openPaymentModal()" 
                        :disabled="cart.length === 0"
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-semibold rounded-xl flex items-center justify-center gap-2 shadow-md shadow-blue-500/20 transition-all text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                    <span>Bayar (Cash / QRIS)</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </button>
                <button @click="clearCart()" 
                        :disabled="cart.length === 0"
                        class="w-full py-2 bg-slate-100 hover:bg-slate-200/70 disabled:opacity-40 text-slate-700 font-medium rounded-xl transition-colors text-xs">
                    Kosongkan Keranjang
                </button>
            </div>
        </div>
    </aside>
    <!-- END: CartPanel -->

    <!-- ==================== MODAL 1: PEMILIHAN PEMBAYARAN ==================== -->
    <div x-show="modalPaymentOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalPaymentOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalPaymentOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="modalPaymentOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Pembayaran Kasir</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pilih metode bayar Tunai atau QRIS.</p>
                    </div>
                    <button @click="modalPaymentOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Total Amount Banner -->
                <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-4 my-4">
                    <span class="text-xs font-medium text-blue-700">Total Tagihan:</span>
                    <div class="text-2xl font-extrabold text-blue-900" x-text="formatRupiah(calculateGrandTotal())"></div>
                </div>

                <!-- Payment Method Tabs -->
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <button @click="paymentMethod = 'cash'; onPaymentMethodChange()" 
                            :class="paymentMethod === 'cash' ? 'border-blue-600 bg-blue-50/50 text-blue-700 font-bold' : 'border-slate-200 text-slate-600 hover:bg-slate-50 font-medium'"
                            class="border rounded-xl p-3 flex items-center justify-center gap-2 text-xs transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Tunai (Cash)</span>
                    </button>
                    <button @click="paymentMethod = 'qris'; onPaymentMethodChange()" 
                            :class="paymentMethod === 'qris' ? 'border-blue-600 bg-blue-50/50 text-blue-700 font-bold' : 'border-slate-200 text-slate-600 hover:bg-slate-50 font-medium'"
                            class="border rounded-xl p-3 flex items-center justify-center gap-2 text-xs transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span>QRIS Statis/Dinamis</span>
                    </button>
                </div>

                <!-- TAB CONTENT: CASH -->
                <div x-show="paymentMethod === 'cash'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Uang Diterima dari Pelanggan (Rp) <span class="text-rose-500">*</span></label>
                        <input x-model.number="amountPaid" 
                               type="number" 
                               class="w-full text-base font-bold border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Contoh: 100000"/>
                    </div>

                    <!-- Quick Cash Amounts -->
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 block mb-1.5">Pilihan Uang Pas / Pecahan:</span>
                        <div class="grid grid-cols-4 gap-2">
                            <button @click="amountPaid = calculateGrandTotal()" type="button" class="px-2 py-1.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-colors">
                                Uang Pas
                            </button>
                            <button @click="amountPaid = 50000" type="button" class="px-2 py-1.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                                50.000
                            </button>
                            <button @click="amountPaid = 100000" type="button" class="px-2 py-1.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                                100.000
                            </button>
                            <button @click="amountPaid = 200000" type="button" class="px-2 py-1.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                                200.000
                            </button>
                        </div>
                    </div>

                    <!-- Change Calculation Box -->
                    <div class="p-3.5 rounded-xl border" 
                         :class="(amountPaid - calculateGrandTotal()) >= 0 ? 'bg-emerald-50/60 border-emerald-200' : 'bg-rose-50/60 border-rose-200'">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-medium text-slate-600" x-text="(amountPaid - calculateGrandTotal()) >= 0 ? 'Uang Kembalian:' : 'Kurang Bayar:'"></span>
                            <span class="text-base font-extrabold" 
                                  :class="(amountPaid - calculateGrandTotal()) >= 0 ? 'text-emerald-700' : 'text-rose-600'" 
                                  x-text="formatRupiah(Math.abs(amountPaid - calculateGrandTotal()))"></span>
                        </div>
                    </div>
                </div>

                <!-- TAB CONTENT: QRIS -->
                <div x-show="paymentMethod === 'qris'" class="space-y-3 text-center py-2">
                    <div class="inline-block p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
                        <!-- Simulated QR Code SVG -->
                        <div class="w-48 h-48 mx-auto bg-slate-50 border border-slate-300 rounded-xl flex flex-col items-center justify-center p-3">
                            <svg class="w-36 h-36 text-slate-900" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-3 0h2v3h-2v-3zm3 3h3v5h-2v-3h-1v-2zm-5 2h2v3h-2v-3zm3 2h2v1h-2v-1z"/>
                            </svg>
                            <span class="text-[10px] font-bold text-slate-600 mt-1 uppercase tracking-wider">QRIS STANDAR NASIONAL</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500">Mendukung GoPay, OVO, Dana, ShopeePay, BCA, Mandiri, BRI, dll.</p>
                </div>

                <!-- Footer Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100 mt-5">
                    <button @click="modalPaymentOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button @click="processCheckout()" 
                            :disabled="submitting || (paymentMethod === 'cash' && amountPaid < calculateGrandTotal())"
                            type="button" 
                            class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-blue-500/20 transition-all flex items-center gap-2">
                        <span x-show="!submitting">Proses &amp; Selesaikan Transaksi</span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL 2: CETAK STRUK THERMAL ==================== -->
    <div x-show="modalReceiptOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalReceiptOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

            <!-- Receipt Modal Panel -->
            <div x-show="modalReceiptOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-sm p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-200 my-8">
                
                <!-- Printable Thermal Receipt Container -->
                <div id="printable-thermal-receipt" class="font-mono text-xs text-slate-800 bg-white select-text">
                    <!-- Store Header -->
                    <div class="text-center pb-3 border-b border-dashed border-slate-400">
                        <h2 class="text-base font-extrabold tracking-tight uppercase" x-text="receiptData?.store_name || 'UMKMGO STORE'"></h2>
                        <p class="text-[10px] text-slate-500 mt-0.5">Sistem Kasir Pintar UMKMGo</p>
                        <p class="text-[10px] text-slate-500" x-text="receiptData?.date || '-'"></p>
                    </div>

                    <!-- Meta Information -->
                    <div class="py-2.5 border-b border-dashed border-slate-400 text-[11px] space-y-0.5">
                        <div class="flex justify-between">
                            <span>No. Struk:</span>
                            <span class="font-bold" x-text="receiptData?.receipt_number"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kasir:</span>
                            <span x-text="receiptData?.cashier_name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pelanggan:</span>
                            <span x-text="receiptData?.customer_name"></span>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="py-2.5 border-b border-dashed border-slate-400 space-y-2 text-[11px]">
                        <template x-for="item in receiptData?.items || []" :key="item.variant_id || item.product_name">
                            <div>
                                <div class="font-bold text-slate-900" x-text="item.product_name"></div>
                                <div class="flex justify-between text-slate-600 text-[10px]">
                                    <span x-text="item.quantity + ' x ' + formatRupiah(item.unit_price)"></span>
                                    <span class="font-medium text-slate-900" x-text="formatRupiah(item.line_total)"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Financial Summary -->
                    <div class="py-2.5 border-b border-dashed border-slate-400 text-[11px] space-y-1">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span x-text="receiptData?.subtotal_formatted"></span>
                        </div>
                        <template x-if="receiptData?.discount > 0">
                            <div class="flex justify-between text-rose-600">
                                <span>Diskon:</span>
                                <span x-text="'-' + receiptData?.discount_formatted"></span>
                            </div>
                        </template>
                        <div class="flex justify-between font-extrabold text-sm pt-1 border-t border-slate-200">
                            <span>TOTAL:</span>
                            <span x-text="receiptData?.total_formatted"></span>
                        </div>
                    </div>

                    <!-- Payment & Change -->
                    <div class="py-2.5 border-b border-dashed border-slate-400 text-[11px] space-y-1">
                        <div class="flex justify-between">
                            <span>Metode:</span>
                            <span class="font-bold" x-text="receiptData?.payment_method"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Bayar:</span>
                            <span x-text="receiptData?.amount_paid_formatted"></span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>Kembali:</span>
                            <span x-text="receiptData?.change_formatted"></span>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="text-center pt-3 text-[10px] text-slate-500">
                        <p class="font-semibold text-slate-700">*** TERIMA KASIH ***</p>
                        <p class="mt-0.5">Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</p>
                    </div>
                </div>

                <!-- Action Buttons (Print / New Trx) -->
                <div class="flex items-center gap-2 pt-4 border-t border-slate-100 mt-4">
                    <button @click="printReceipt()" 
                            type="button" 
                            class="flex-1 bg-slate-900 hover:bg-slate-800 text-white py-2.5 rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>Cetak Struk</span>
                    </button>
                    <button @click="newTransaction()" 
                            type="button" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path>
                        </svg>
                        <span>Transaksi Baru</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function posCashier() {
        return {
            products: [],
            filteredProducts: [],
            categories: [],
            customerOptions: [],
            activeShiftInfo: null,
            loading: false,
            submitting: false,

            // Filter states
            searchQuery: '',
            selectedCategory: 'all',

            // Cart state
            cart: [],
            selectedCustomer: '',
            discountAmount: 0,
            transactionNote: '',
            currentTrxCode: 'TRX-' + Math.floor(10000 + Math.random() * 90000),

            // Modals
            modalPaymentOpen: false,
            modalReceiptOpen: false,
            paymentMethod: 'cash',
            amountPaid: 0,
            receiptData: null,

            async init() {
                // Keyboard shortcut '/' to focus search input
                window.addEventListener('keydown', (e) => {
                    if (e.key === '/' && document.activeElement !== this.$refs.searchInput) {
                        e.preventDefault();
                        this.$refs.searchInput?.focus();
                    }
                });

                await Promise.all([
                    this.fetchCatalog(),
                    this.fetchCategories(),
                    this.fetchCustomers(),
                    this.fetchShift()
                ]);
            },

            async fetchCatalog() {
                this.loading = true;
                try {
                    const res = await fetch('/api/v1/operasional/kasir/catalog');
                    const json = await res.json();
                    if (json.success) {
                        this.products = json.data.products;
                        this.filterProducts();
                    }
                } catch (e) {
                    console.error('Error fetching catalog:', e);
                    window.showToast('Gagal memuat katalog produk', 'error');
                } finally {
                    this.loading = false;
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
                    console.error('Error fetching categories:', e);
                }
            },

            async fetchCustomers() {
                try {
                    const res = await fetch('/api/v1/operasional/kasir/customers');
                    const json = await res.json();
                    if (json.success) {
                        this.customerOptions = json.data;
                    }
                } catch (e) {
                    console.error('Error fetching customers:', e);
                }
            },

            async fetchShift() {
                try {
                    const res = await fetch('/api/v1/operasional/kasir/shift');
                    const json = await res.json();
                    if (json.success) {
                        this.activeShiftInfo = json.data;
                    }
                } catch (e) {
                    console.error('Error fetching shift:', e);
                }
            },

            setCategory(categorySlug) {
                this.selectedCategory = categorySlug;
                this.filterProducts();
            },

            filterProducts() {
                let list = [...this.products];

                if (this.selectedCategory !== 'all') {
                    list = list.filter(p => {
                        const cat = (p.category_name || '').toLowerCase();
                        const target = this.selectedCategory.toLowerCase();
                        return cat.includes(target);
                    });
                }

                if (this.searchQuery.trim()) {
                    const q = this.searchQuery.toLowerCase().trim();
                    list = list.filter(p => 
                        p.name.toLowerCase().includes(q) || 
                        (p.sku && p.sku.toLowerCase().includes(q))
                    );
                }

                this.filteredProducts = list;
            },

            getItemQtyInCart(product) {
                const item = this.cart.find(c => c.variant_id === product.variant_id);
                return item ? item.quantity : 0;
            },

            get totalCartItemsCount() {
                return this.cart.reduce((sum, item) => sum + item.quantity, 0);
            },

            addToCart(product) {
                if (product.stock_quantity <= 0) {
                    window.showToast('Stok produk ini sedang habis!', 'warning');
                    return;
                }

                const existingIndex = this.cart.findIndex(c => c.variant_id === product.variant_id);

                if (existingIndex > -1) {
                    if (this.cart[existingIndex].quantity + 1 > product.stock_quantity) {
                        window.showToast(`Maksimal stok tersedia hanya ${product.stock_quantity} unit!`, 'warning');
                        return;
                    }
                    this.cart[existingIndex].quantity += 1;
                } else {
                    this.cart.push({
                        product_id: product.id,
                        variant_id: product.variant_id,
                        name: product.name,
                        variant_name: product.variant_name,
                        price: product.price,
                        quantity: 1,
                        max_stock: product.stock_quantity
                    });
                }
            },

            incrementQty(index) {
                const item = this.cart[index];
                if (item.quantity + 1 > item.max_stock) {
                    window.showToast(`Maksimal stok tercapai (${item.max_stock} unit)`, 'warning');
                    return;
                }
                item.quantity += 1;
            },

            decrementQty(index) {
                if (this.cart[index].quantity > 1) {
                    this.cart[index].quantity -= 1;
                } else {
                    this.removeFromCart(index);
                }
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            clearCart() {
                this.cart = [];
                this.discountAmount = 0;
                this.transactionNote = '';
            },

            calculateSubtotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },

            calculateGrandTotal() {
                const sub = this.calculateSubtotal();
                const disc = parseFloat(this.discountAmount) || 0;
                return Math.max(0, sub - disc);
            },

            openPaymentModal() {
                if (this.cart.length === 0) {
                    window.showToast('Keranjang belanja masih kosong', 'warning');
                    return;
                }
                this.paymentMethod = 'cash';
                this.amountPaid = this.calculateGrandTotal();
                this.modalPaymentOpen = true;
            },

            onPaymentMethodChange() {
                if (this.paymentMethod === 'qris') {
                    this.amountPaid = this.calculateGrandTotal();
                } else {
                    this.amountPaid = this.calculateGrandTotal();
                }
            },

            async processCheckout() {
                const grandTotal = this.calculateGrandTotal();

                if (this.paymentMethod === 'cash' && this.amountPaid < grandTotal) {
                    window.showToast('Uang pembayaran masih kurang!', 'error');
                    return;
                }

                this.submitting = true;
                try {
                    const customerObj = this.customerOptions.find(c => c.id === this.selectedCustomer);

                    const payload = {
                        customer_user_id: this.selectedCustomer || null,
                        customer_name: customerObj?.full_name || 'Pelanggan Umum',
                        items: this.cart.map(item => ({
                            variant_id: item.variant_id,
                            quantity: item.quantity,
                            unit_price: item.price
                        })),
                        discount_amount: parseFloat(this.discountAmount) || 0,
                        tax_amount: 0,
                        payment_method: this.paymentMethod,
                        amount_paid: parseFloat(this.amountPaid) || grandTotal,
                        note: this.transactionNote || null
                    };

                    const res = await fetch('/api/v1/operasional/kasir/checkout', {
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
                        this.receiptData = json.data;
                        this.modalPaymentOpen = false;
                        this.modalReceiptOpen = true;
                        window.showToast('Pembayaran berhasil diproses!', 'success');
                        await this.fetchCatalog(); // Refresh catalog stocks
                    } else {
                        window.showToast(json.message || 'Gagal memproses transaksi', 'error');
                    }
                } catch (e) {
                    console.error('Checkout error:', e);
                    window.showToast('Terjadi kesalahan sistem saat memproses transaksi', 'error');
                } finally {
                    this.submitting = false;
                }
            },

            printReceipt() {
                window.print();
            },

            newTransaction() {
                this.clearCart();
                this.modalReceiptOpen = false;
                this.receiptData = null;
                this.currentTrxCode = 'TRX-' + Math.floor(10000 + Math.random() * 90000);
            },

            formatRupiah(num) {
                return 'Rp ' + (num || 0).toLocaleString('id-ID');
            }
        };
    }
</script>
@endpush
