@extends('layouts.admin')

@section('title', 'UMKMGo - Pesanan Online')

@section('content')
<main class="p-8 max-w-[1300px] w-full mx-auto" 
      data-purpose="main-layout"
      x-data="ordersManagement()"
      x-init="init()">
    
    <!-- Title & Subtitle Section -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pesanan Online</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau dan kelola pesanan masuk dari toko online &amp; delivery secara real-time.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="fetchOrders(1)" 
                    class="px-3.5 py-2 text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" :class="loading ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Muat Ulang</span>
            </button>
        </div>
    </div>

    <!-- Horizontal Tabs Navigation -->
    <div class="border-b border-slate-200 mb-6 flex items-center gap-6 text-sm overflow-x-auto no-scrollbar">
        <!-- Tab: Semua -->
        <button @click="setTab('all')" 
                :class="activeTab === 'all' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-slate-500 hover:text-slate-800 border-b-2 border-transparent'"
                class="pb-3 px-1 transition-colors flex items-center gap-2 whitespace-nowrap">
            <span>Semua</span>
            <span class="text-xs text-slate-500 font-semibold" x-text="'(' + counts.all + ')'"></span>
        </button>

        <!-- Tab: Pesanan Baru -->
        <button @click="setTab('new')" 
                :class="activeTab === 'new' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-slate-500 hover:text-slate-800 border-b-2 border-transparent'"
                class="pb-3 px-1 transition-colors flex items-center gap-2 whitespace-nowrap">
            <span>Pesanan Baru</span>
            <span class="text-xs text-emerald-600 font-bold" x-text="'(' + counts.new + ')'"></span>
        </button>

        <!-- Tab: Diproses -->
        <button @click="setTab('processing')" 
                :class="activeTab === 'processing' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-slate-500 hover:text-slate-800 border-b-2 border-transparent'"
                class="pb-3 px-1 transition-colors flex items-center gap-2 whitespace-nowrap">
            <span>Diproses</span>
            <span class="text-xs text-blue-600 font-bold" x-text="'(' + counts.processing + ')'"></span>
        </button>

        <!-- Tab: Dalam Pengiriman -->
        <button @click="setTab('shipped')" 
                :class="activeTab === 'shipped' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-slate-500 hover:text-slate-800 border-b-2 border-transparent'"
                class="pb-3 px-1 transition-colors flex items-center gap-2 whitespace-nowrap">
            <span>Dalam Pengiriman</span>
            <span class="text-xs text-amber-600 font-bold" x-text="'(' + counts.shipped + ')'"></span>
        </button>

        <!-- Tab: Selesai -->
        <button @click="setTab('delivered')" 
                :class="activeTab === 'delivered' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-slate-500 hover:text-slate-800 border-b-2 border-transparent'"
                class="pb-3 px-1 transition-colors flex items-center gap-2 whitespace-nowrap">
            <span>Selesai</span>
            <span class="text-xs text-slate-500 font-bold" x-text="'(' + counts.delivered + ')'"></span>
        </button>

        <!-- Tab: Dibatalkan -->
        <button @click="setTab('cancelled')" 
                :class="activeTab === 'cancelled' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-slate-500 hover:text-slate-800 border-b-2 border-transparent'"
                class="pb-3 px-1 transition-colors flex items-center gap-2 whitespace-nowrap">
            <span>Dibatalkan</span>
            <span class="text-xs text-rose-500 font-bold" x-text="'(' + counts.cancelled + ')'"></span>
        </button>
    </div>

    <!-- Filter and Search Row -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3 flex-1 max-w-2xl">
            <!-- Search Bar -->
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </span>
                <input x-model.debounce.300ms="searchQuery"
                       @input="fetchOrders(1)"
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-xs" 
                       placeholder="Cari nomor pesanan, nama pelanggan, resi..." 
                       type="text"/>
            </div>

            <!-- Dropdown Courier Filter -->
            <div class="relative flex-shrink-0">
                <select x-model="selectedCourier" 
                        @change="fetchOrders(1)" 
                        class="bg-white border border-slate-200 pl-3.5 pr-8 py-2.5 rounded-xl text-xs font-medium text-slate-700 shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer appearance-none">
                    <option value="all">Semua Kurir</option>
                    <template x-for="c in courierList" :key="c.code">
                        <option :value="c.code" x-text="c.name"></option>
                    </template>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </div>
            </div>
        </div>

        <!-- Status Right Indication -->
        <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
            <span class="text-xs text-slate-600 font-semibold" x-text="pagination.total + ' Pesanan Terdaftar'"></span>
        </div>
    </div>

    <!-- BEGIN: OrderListCards Container -->
    <div class="relative min-h-[250px]" data-purpose="orders-list">
        <!-- Loading Overlay -->
        <div x-show="loading" class="py-16 flex flex-col items-center justify-center text-slate-400">
            <svg class="animate-spin h-7 w-7 text-blue-600 mb-2" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-xs font-medium">Memuat daftar pesanan...</span>
        </div>

        <div class="overflow-x-auto pb-3 -mx-2 px-2">
            <div class="min-w-[980px] space-y-3.5">
                <!-- Dynamic Order Cards -->
                <template x-for="order in orders" :key="order.id">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 flex items-center justify-between gap-4 shadow-xs hover:border-blue-200 transition-all">
                        <!-- 1. Left: Product Image & Order Info -->
                        <div class="flex items-center gap-3.5 w-[250px] flex-shrink-0">
                            <img :src="order.thumbnail" 
                                 :alt="order.order_number" 
                                 class="w-12 h-12 rounded-xl object-cover flex-shrink-0 border border-slate-100 bg-slate-50"
                                 onerror="this.src='/images/dashboard/coffee-americano.jpg'"/>
                            <div class="min-w-0">
                                <span class="block font-bold text-slate-900 text-sm font-mono tracking-tight whitespace-nowrap" x-text="order.order_number"></span>
                                <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium mt-1 truncate">
                                    <span x-text="order.courier"></span>
                                    <template x-if="order.tracking_number">
                                        <span class="font-mono text-[11px] text-slate-400 font-semibold" x-text="'(' + order.tracking_number + ')'"></span>
                                    </template>
                                </div>
                                <span class="block text-[11px] text-slate-400 mt-0.5 whitespace-nowrap" x-text="order.placed_at"></span>
                            </div>
                        </div>

                        <!-- 2. Status Column -->
                        <div class="w-[120px] flex-shrink-0">
                            <span class="block text-[11px] text-slate-400 font-medium">Status</span>
                            <span class="block text-xs font-bold mt-0.5 whitespace-nowrap"
                                  :class="{
                                      'text-emerald-600': order.status === 'pending' || order.status === 'paid',
                                      'text-blue-600': order.status === 'processing',
                                      'text-amber-600': order.status === 'shipped',
                                      'text-slate-600': order.status === 'delivered',
                                      'text-rose-600': order.status === 'cancelled'
                                  }"
                                  x-text="order.status_badge?.label"></span>
                        </div>

                        <!-- 3. Items Ordered Summary -->
                        <div class="w-[240px] flex-shrink-0">
                            <span class="block text-[11px] text-slate-400 font-medium">Item Pesanan:</span>
                            <p class="text-xs font-medium text-slate-700 line-clamp-2 mt-0.5" x-text="order.items_summary"></p>
                        </div>

                        <!-- 4. Customer Name -->
                        <div class="w-[140px] flex-shrink-0">
                            <span class="block text-[11px] text-slate-400 font-medium">Pembeli</span>
                            <p class="text-xs font-bold text-slate-900 leading-tight mt-0.5 truncate" x-text="order.buyer_name"></p>
                            <span class="text-[11px] text-slate-400 font-mono block" x-text="order.buyer_phone"></span>
                        </div>

                        <!-- 5. Total Price -->
                        <div class="w-[120px] flex-shrink-0">
                            <span class="block text-[11px] text-slate-400 font-medium">Total Tagihan</span>
                            <p class="text-sm font-extrabold text-slate-900 leading-tight mt-0.5 whitespace-nowrap" x-text="order.total_formatted"></p>
                        </div>

                        <!-- 6. Actions Based on Status -->
                        <div class="flex items-center gap-2 flex-shrink-0 justify-end w-[180px]">
                            <!-- STATUS: NEW / PENDING / PAID -->
                            <template x-if="order.status === 'pending' || order.status === 'paid'">
                                <div class="flex items-center gap-2 w-full justify-end">
                                    <button @click="openCancelModal(order)" 
                                            type="button" 
                                            class="px-3.5 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                                        Tolak
                                    </button>
                                    <button @click="acceptOrder(order)" 
                                            type="button" 
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-xs font-semibold text-white shadow-xs transition-colors whitespace-nowrap">
                                        Terima &amp; Proses
                                    </button>
                                </div>
                            </template>

                            <!-- STATUS: PROCESSING -->
                            <template x-if="order.status === 'processing'">
                                <div class="w-full">
                                    <button @click="openShipModal(order)" 
                                            type="button" 
                                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-xs font-semibold text-white shadow-xs transition-colors flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Kirim Pesanan (Resi)</span>
                                    </button>
                                </div>
                            </template>

                            <!-- STATUS: SHIPPED -->
                            <template x-if="order.status === 'shipped'">
                                <div class="flex items-center gap-2 w-full justify-end">
                                    <button @click="completeOrder(order)" 
                                            type="button" 
                                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-xl text-xs font-semibold text-white shadow-xs transition-colors whitespace-nowrap">
                                        Tandai Selesai
                                    </button>
                                    <button @click="openDetailModal(order)" 
                                            type="button" 
                                            class="px-3 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition-colors">
                                        Detail
                                    </button>
                                </div>
                            </template>

                            <!-- STATUS: DELIVERED OR CANCELLED -->
                            <template x-if="order.status === 'delivered' || order.status === 'cancelled'">
                                <div class="w-full flex justify-end">
                                    <button @click="openDetailModal(order)" 
                                            type="button" 
                                            class="px-5 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition-colors whitespace-nowrap">
                                        Lihat Detail
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Empty Results State -->
        <div x-show="!loading && orders.length === 0" class="py-16 text-center bg-white rounded-2xl border border-slate-200">
            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-2.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-800">Tidak ada pesanan ditemukan</h3>
            <p class="text-xs text-slate-400 mt-1">Belum ada pesanan masuk untuk status filter ini.</p>
        </div>
    </div>
    <!-- END: OrderListCards Container -->

    <!-- BEGIN: PaginationFooter -->
    <div class="flex items-center justify-between mt-8 text-xs text-slate-500" data-purpose="pagination-controls">
        <p>Menampilkan <span class="font-medium text-slate-800" x-text="pagination.from || 0"></span> - <span class="font-medium text-slate-800" x-text="pagination.to || 0"></span> dari <span class="font-medium text-slate-800" x-text="pagination.total || 0"></span> pesanan</p>
        <div class="flex items-center gap-1.5">
            <!-- Prev Button -->
            <button @click="fetchOrders(pagination.current_page - 1)" 
                    :disabled="pagination.current_page <= 1"
                    :class="pagination.current_page <= 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 text-slate-700'"
                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            
            <template x-for="p in pagination.last_page" :key="p">
                <button @click="fetchOrders(p)"
                        x-show="p === 1 || p === pagination.last_page || Math.abs(p - pagination.current_page) <= 1"
                        :class="p === pagination.current_page ? 'bg-blue-600 text-white font-bold' : 'border border-slate-200 text-slate-600 hover:bg-slate-50 font-normal'"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors"
                        x-text="p"></button>
            </template>

            <!-- Next Button -->
            <button @click="fetchOrders(pagination.current_page + 1)" 
                    :disabled="pagination.current_page >= pagination.last_page"
                    :class="pagination.current_page >= pagination.last_page ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 text-slate-700'"
                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>
    <!-- END: PaginationFooter -->

    <!-- ==================== MODAL 1: INPUT KURIR & RESI PENGIRIMAN ==================== -->
    <div x-show="modalShipOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalShipOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalShipOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="modalShipOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Kirim Pesanan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Input kurir pengiriman dan nomor resi untuk pesanan <strong class="text-slate-800" x-text="selectedOrder?.order_number"></strong>.</p>
                    </div>
                    <button @click="modalShipOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="submitShipOrder()" class="mt-4 space-y-4">
                    <!-- Recipient Destination Preview -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-600">
                        <span class="block font-semibold text-slate-800 mb-0.5" x-text="selectedOrder ? ('Penerima: ' + (selectedOrder.buyer_name || '-')) : 'Penerima: -'"></span>
                        <p x-text="selectedOrder?.shipping_address?.address_line1 || 'Alamat tujuan pengiriman pelanggan'"></p>
                    </div>

                    <!-- Courier Selection -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kurir Pengiriman <span class="text-rose-500">*</span></label>
                        <select x-model="shipForm.courier" required class="w-full text-xs border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Jasa Ekspedisi / Kurir</option>
                            <template x-for="c in courierList" :key="c.code">
                                <option :value="c.name" x-text="c.name"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Service Name -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Layanan Ekspedisi</label>
                        <input x-model="shipForm.service_name" type="text" placeholder="Contoh: Reguler, EZ, Hemat, Same Day" class="w-full text-xs border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <!-- Tracking Number (Resi) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Resi Pengiriman <span class="text-rose-500">*</span></label>
                        <input x-model="shipForm.tracking_number" required type="text" placeholder="Contoh: JNE1098234726, JT8823901..." class="w-full text-xs font-mono font-bold tracking-wider border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <!-- Note -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <input x-model="shipForm.note" type="text" placeholder="Catatan untuk kurir atau pembeli..." class="w-full text-xs border border-slate-200 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button @click="modalShipOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button :disabled="submitting" type="submit" class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-5 py-2.5 rounded-xl text-xs font-semibold shadow-xs transition-colors flex items-center gap-1.5">
                            <span x-show="!submitting">Simpan &amp; Kirim Pesanan</span>
                            <span x-show="submitting">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL 2: DETAIL PESANAN ==================== -->
    <div x-show="modalDetailOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalDetailOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalDetailOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="modalDetailOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-slate-900" x-text="detailOrder ? ('Rincian ' + detailOrder.order_number) : 'Rincian Pesanan'"></h3>
                            <span class="text-xs font-semibold capitalize" 
                                  :class="{
                                      'text-emerald-600': detailOrder?.status === 'pending' || detailOrder?.status === 'paid',
                                      'text-blue-600': detailOrder?.status === 'processing',
                                      'text-amber-600': detailOrder?.status === 'shipped',
                                      'text-slate-600': detailOrder?.status === 'delivered',
                                      'text-rose-600': detailOrder?.status === 'cancelled'
                                  }"
                                  x-text="detailOrder?.status || ''"></span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5" x-text="detailOrder ? ('Waktu Pemesanan: ' + (detailOrder.placed_at || '-')) : ''"></p>
                    </div>
                    <button @click="modalDetailOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4 text-xs">
                    <!-- Delivery Info Box -->
                    <div class="grid grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase">Informasi Pembeli &amp; Alamat:</span>
                            <div class="font-bold text-slate-800 text-xs mt-1" x-text="detailOrder?.buyer_name"></div>
                            <div class="text-slate-500 font-mono text-[11px]" x-text="detailOrder?.buyer_phone"></div>
                            <p class="text-slate-600 text-[11px] mt-1" x-text="detailOrder?.shipping_address?.address_line1 || '-'"></p>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase">Ekspedisi / Kurir:</span>
                            <div class="font-bold text-slate-800 text-xs mt-1" x-text="detailOrder?.courier"></div>
                            <div class="text-slate-600 text-[11px] mt-0.5">
                                No. Resi: <strong class="font-mono text-slate-900" x-text="detailOrder?.tracking_number || 'Belum diinput'"></strong>
                            </div>
                            <template x-if="detailOrder?.buyer_note">
                                <p class="text-[11px] text-slate-500 mt-1 italic" x-text="'Catatan: ' + detailOrder?.buyer_note"></p>
                            </template>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div>
                        <span class="text-xs font-bold text-slate-800 uppercase tracking-wider block mb-2">Item Produk Dibeli:</span>
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead class="bg-slate-50 border-b border-slate-200 font-semibold text-slate-600">
                                    <tr>
                                        <th class="py-2.5 px-3">Nama Produk</th>
                                        <th class="py-2.5 px-3 text-right">Harga Satuan</th>
                                        <th class="py-2.5 px-3 text-center">Jumlah</th>
                                        <th class="py-2.5 px-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="item in detailOrder?.items || []" :key="item.id">
                                        <tr>
                                            <td class="py-2 px-3 font-medium text-slate-900" x-text="item.product_name_snapshot"></td>
                                            <td class="py-2 px-3 text-right text-slate-600" x-text="formatRupiah(item.unit_price)"></td>
                                            <td class="py-2 px-3 text-center font-bold text-slate-800" x-text="item.quantity"></td>
                                            <td class="py-2 px-3 text-right font-bold text-slate-900" x-text="formatRupiah(item.line_total)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Summary Box -->
                    <div class="space-y-1.5 p-3.5 bg-slate-50/70 rounded-xl border border-slate-200 text-xs">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal Barang:</span>
                            <span class="font-medium text-slate-800" x-text="formatRupiah(detailOrder?.subtotal_amount)"></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Ongkos Kirim:</span>
                            <span class="font-medium text-slate-800" x-text="formatRupiah(detailOrder?.shipping_amount)"></span>
                        </div>
                        <template x-if="detailOrder?.discount_amount > 0">
                            <div class="flex justify-between text-rose-600">
                                <span>Diskon:</span>
                                <span class="font-medium" x-text="'-' + formatRupiah(detailOrder?.discount_amount)"></span>
                            </div>
                        </template>
                        <div class="flex justify-between font-extrabold text-sm text-slate-900 pt-1.5 border-t border-slate-200">
                            <span>TOTAL PEMBAYARAN:</span>
                            <span class="text-blue-600" x-text="detailOrder?.total_formatted"></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-slate-100 mt-4">
                    <button @click="modalDetailOpen = false" type="button" class="px-5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL 3: TOLAK / BATALKAN PESANAN ==================== -->
    <div x-show="modalCancelOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalCancelOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalCancelOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="modalCancelOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <h3 class="text-base font-bold text-slate-900">Batalkan Pesanan Ini?</h3>
                <p class="text-xs text-slate-500 mt-1">Pesanan <strong class="text-slate-800" x-text="selectedOrder ? selectedOrder.order_number : ''"></strong> akan dibatalkan dan stok produk akan otomatis dikembalikan ke inventaris.</p>

                <form @submit.prevent="submitCancelOrder()">
                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Pembatalan <span class="text-rose-500">*</span></label>
                        <input x-model="cancelReason" required minlength="5" type="text" placeholder="Contoh: Stok barang habis, toko tutup..." class="w-full text-xs border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-rose-500"/>
                        <p class="text-[11px] text-slate-400 mt-1">Wajib diisi minimal 5 karakter sebagai catatan audit inventaris.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <button @click="modalCancelOpen = false" type="button" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button :disabled="submitting || !cancelReason.trim() || cancelReason.trim().length < 5" type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-xs transition-colors disabled:opacity-50">
                            <span x-show="!submitting">Ya, Batalkan Pesanan</span>
                            <span x-show="submitting">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function ordersManagement() {
        return {
            orders: [],
            courierList: [],
            activeTab: 'all',
            searchQuery: '',
            selectedCourier: 'all',
            loading: false,
            submitting: false,
            counts: {
                all: 0,
                new: 0,
                processing: 0,
                shipped: 0,
                delivered: 0,
                cancelled: 0
            },
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                from: 0,
                to: 0
            },

            // Modals
            modalShipOpen: false,
            modalDetailOpen: false,
            modalCancelOpen: false,
            selectedOrder: null,
            detailOrder: null,
            cancelReason: '',
            shipForm: {
                courier: '',
                service_name: 'Reguler',
                tracking_number: '',
                note: ''
            },

            async init() {
                await Promise.all([
                    this.fetchCouriers(),
                    this.fetchOrders(1)
                ]);
            },

            async fetchCouriers() {
                try {
                    const res = await fetch('/api/v1/operasional/pesanan-online/couriers');
                    const json = await res.json();
                    if (json.success) {
                        this.courierList = json.data;
                    }
                } catch (e) {
                    console.error('Failed to load couriers', e);
                }
            },

            setTab(tab) {
                this.activeTab = tab;
                this.fetchOrders(1);
            },

            async fetchOrders(page = 1) {
                this.loading = true;
                try {
                    const params = new URLSearchParams({
                        page: page,
                        per_page: 8,
                        status: this.activeTab
                    });

                    if (this.searchQuery.trim()) {
                        params.append('search', this.searchQuery.trim());
                    }
                    if (this.selectedCourier && this.selectedCourier !== 'all') {
                        params.append('courier', this.selectedCourier);
                    }

                    const res = await fetch(`/api/v1/operasional/pesanan-online?${params.toString()}`);
                    const json = await res.json();

                    if (json.success) {
                        this.orders = json.data;
                        if (json.counts) {
                            this.counts = json.counts;
                        }
                        this.pagination = {
                            current_page: json.meta?.current_page || 1,
                            last_page: json.meta?.last_page || 1,
                            total: json.meta?.total || 0,
                            from: json.meta?.from || 0,
                            to: json.meta?.to || 0,
                        };
                    } else {
                        window.showToast(json.message || 'Gagal memuat pesanan', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Gagal terhubung ke server pesanan', 'error');
                } finally {
                    this.loading = false;
                }
            },

            async acceptOrder(order) {
                try {
                    const res = await fetch(`/api/v1/operasional/pesanan-online/${order.id}/accept`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });

                    const json = await res.json();
                    if (json.success) {
                        window.showToast(`Pesanan ${order.order_number} berhasil diterima!`, 'success');
                        await this.fetchOrders(this.pagination.current_page);
                    } else {
                        window.showToast(json.message || 'Gagal menerima pesanan', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Terjadi kesalahan saat memproses pesanan', 'error');
                }
            },

            openShipModal(order) {
                this.selectedOrder = order;
                this.shipForm = {
                    courier: order.courier && order.courier !== 'Kurir Toko' ? order.courier : (this.courierList.length > 0 ? this.courierList[0].name : 'JNE Express'),
                    service_name: order.service_name || 'Reguler',
                    tracking_number: '',
                    note: ''
                };
                this.modalShipOpen = true;
            },

            async submitShipOrder() {
                if (!this.selectedOrder) return;
                this.submitting = true;
                try {
                    const payload = {
                        courier: this.shipForm.courier,
                        service_name: this.shipForm.service_name,
                        tracking_number: this.shipForm.tracking_number,
                        note: this.shipForm.note || null
                    };

                    const res = await fetch(`/api/v1/operasional/pesanan-online/${this.selectedOrder.id}/ship`, {
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
                        this.modalShipOpen = false;
                        window.showToast(`Pesanan ${this.selectedOrder.order_number} berhasil dikirim dengan no. resi ${this.shipForm.tracking_number}!`, 'success');
                        await this.fetchOrders(this.pagination.current_page);
                    } else {
                        window.showToast(json.message || 'Gagal memproses pengiriman', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Terjadi kesalahan saat menyimpan resi', 'error');
                } finally {
                    this.submitting = false;
                }
            },

            async completeOrder(order) {
                try {
                    const res = await fetch(`/api/v1/operasional/pesanan-online/${order.id}/complete`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });

                    const json = await res.json();
                    if (json.success) {
                        window.showToast(`Pesanan ${order.order_number} telah ditandai selesai!`, 'success');
                        await this.fetchOrders(this.pagination.current_page);
                    } else {
                        window.showToast(json.message || 'Gagal menyelesaikan pesanan', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Terjadi kesalahan', 'error');
                }
            },

            openCancelModal(order) {
                this.selectedOrder = order;
                this.cancelReason = 'Stok barang habis / tidak dapat dipenuhi';
                this.modalCancelOpen = true;
            },

            async submitCancelOrder() {
                if (!this.selectedOrder || !this.cancelReason.trim()) return;
                this.submitting = true;
                try {
                    const res = await fetch(`/api/v1/operasional/pesanan-online/${this.selectedOrder.id}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ cancel_reason: this.cancelReason })
                    });

                    const json = await res.json();
                    if (json.success) {
                        this.modalCancelOpen = false;
                        window.showToast(`Pesanan ${this.selectedOrder.order_number} telah dibatalkan & stok dikembalikan!`, 'info');
                        await this.fetchOrders(this.pagination.current_page);
                    } else {
                        window.showToast(json.message || 'Gagal membatalkan pesanan', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    window.showToast('Terjadi kesalahan', 'error');
                } finally {
                    this.submitting = false;
                }
            },

            openDetailModal(order) {
                this.detailOrder = order;
                this.modalDetailOpen = true;
            },

            formatRupiah(num) {
                return 'Rp ' + (num || 0).toLocaleString('id-ID');
            }
        };
    }
</script>
@endpush
