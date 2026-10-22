@extends('layouts.admin')

@section('title', 'UMKMGo - Pelanggan')

@section('content')
<main class="p-8 space-y-6 max-w-[1400px] w-full mx-auto" 
      data-purpose="customers-page"
      x-data="customerManagement()" 
      x-init="init()">
    
    <!-- Header Section -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4" data-purpose="header-section">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pelanggan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data pelanggan, riwayat belanja, dan tingkat loyalitas member.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="fetchCustomers(pagination.current_page)" 
                    type="button" 
                    title="Refresh data"
                    class="px-3.5 py-2.5 text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" :class="loading ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Refresh</span>
            </button>
            <button @click="openCreateModal()" 
                    type="button"
                    class="bg-[#2563EB] hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-xs flex items-center gap-2 text-xs sm:text-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span>Tambah Pelanggan</span>
            </button>
        </div>
    </section>

    <!-- BEGIN: MetricCards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6" data-purpose="metrics">
        <!-- Card 1: Total Pelanggan -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <div class="text-[13px] font-medium text-slate-500">Total Pelanggan</div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-3xl font-extrabold text-slate-900" x-text="metrics.total_customers">0</span>
                    <span class="text-xs font-medium text-slate-500">Orang</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
        </div>

        <!-- Card 2: Pelanggan Baru Bulan Ini -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <div class="text-[13px] font-medium text-slate-500">Pelanggan Baru Bulan Ini</div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-3xl font-extrabold text-slate-900" x-text="metrics.new_this_month">0</span>
                    <span class="text-xs font-medium text-slate-500">Orang</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
        </div>

        <!-- Card 3: Member VIP / Top Spender -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <div class="text-[13px] font-medium text-slate-500">Member VIP (Gold/Platinum)</div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-3xl font-extrabold text-slate-900" x-text="metrics.vip_count">0</span>
                    <span class="text-xs font-medium text-slate-500">Orang</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M3 18h18v2H3v-2zm2-2l2.5-9 4.5 4 4.5-4L19 16H5z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
        </div>
    </section>
    <!-- END: MetricCards -->

    <!-- BEGIN: FilterBar -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4" data-purpose="filter-bar">
        <div class="flex items-center gap-3 flex-1 max-w-2xl">
            <!-- Search Input -->
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" x2="16.65" y1="21" y2="16.65"></line>
                    </svg>
                </span>
                <input x-model.debounce.350ms="searchQuery" 
                       @input="fetchCustomers(1)"
                       class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-xs" 
                       placeholder="Cari nama, email, No. WA..." 
                       type="text"/>
            </div>

            <!-- Status Member Dropdown -->
            <div class="relative min-w-[170px]">
                <select x-model="selectedTier" 
                        @change="fetchCustomers(1)"
                        class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer pr-9 shadow-xs">
                    <option value="">Semua Tingkat Member</option>
                    <option value="Platinum">Platinum (&gt; Rp 2.5jt)</option>
                    <option value="Gold">Gold (&gt; Rp 1jt)</option>
                    <option value="Regular">Regular</option>
                </select>
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </span>
            </div>
        </div>

        <!-- Export Data Button -->
        <button @click="exportCSV()" 
                type="button" 
                class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-4 py-2.5 rounded-xl text-xs sm:text-sm flex items-center gap-2 shadow-xs transition-colors">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <span>Ekspor CSV</span>
        </button>
    </section>
    <!-- END: FilterBar -->

    <!-- BEGIN: CustomerTableCard -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden relative min-h-[300px]">
        <!-- Loading Overlay -->
        <div x-show="loading" class="py-20 flex flex-col items-center justify-center text-slate-400">
            <svg class="animate-spin h-7 w-7 text-blue-600 mb-2" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-xs font-medium">Memuat data pelanggan...</span>
        </div>

        <div x-show="!loading" class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200/80 text-[12px] font-semibold text-slate-500 bg-slate-50/50">
                        <th class="py-4 px-6 font-semibold">Nama &amp; Kontak</th>
                        <th class="py-4 px-6 font-semibold">Total Transaksi</th>
                        <th class="py-4 px-6 font-semibold">Total Belanja</th>
                        <th class="py-4 px-6 font-semibold">Poin Loyalitas</th>
                        <th class="py-4 px-6 font-semibold">Status Member</th>
                        <th class="py-4 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <template x-for="cust in customers" :key="cust.id">
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center flex-shrink-0 border border-blue-100"
                                         x-text="cust.initials">
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-sm truncate" x-text="cust.full_name"></div>
                                        <div class="text-xs text-slate-400 mt-0.5 font-mono" x-text="cust.phone"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-700 font-semibold" x-text="cust.total_orders_formatted"></td>
                            <td class="py-4 px-6 font-bold text-slate-900" x-text="cust.total_spent_formatted"></td>
                            <td class="py-4 px-6 text-slate-600 font-medium" x-text="cust.points_formatted"></td>
                            <td class="py-4 px-6">
                                <!-- Clean Member Tag (No bullet dot) -->
                                <span class="inline-block text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap"
                                      :class="cust.tier_badge.color"
                                      x-text="cust.tier_badge.label"></span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2 text-xs font-semibold">
                                    <button @click="openDetailModal(cust)" 
                                            type="button" 
                                            title="Lihat Detail Pelanggan"
                                            class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                        Detail
                                    </button>
                                    <button @click="openEditModal(cust)" 
                                            type="button" 
                                            title="Edit Data"
                                            class="text-slate-600 hover:text-slate-800 hover:bg-slate-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                        Edit
                                    </button>
                                    <button @click="confirmDelete(cust)" 
                                            type="button" 
                                            title="Hapus"
                                            class="text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && customers.length === 0" class="py-16 text-center">
            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-800">Tidak ada data pelanggan</h3>
            <p class="text-xs text-slate-400 mt-1">Belum ada data pelanggan yang cocok dengan pencarian.</p>
        </div>
    </div>
    <!-- END: CustomerTableCard -->

    <!-- BEGIN: PaginationFooter -->
    <div x-show="pagination.total > 0" class="flex items-center justify-between text-xs text-slate-500 pt-2">
        <p>Menampilkan <span class="font-medium text-slate-800" x-text="pagination.from || 0"></span> - <span class="font-medium text-slate-800" x-text="pagination.to || 0"></span> dari <span class="font-medium text-slate-800" x-text="pagination.total || 0"></span> pelanggan</p>
        
        <div class="flex items-center gap-1.5 select-none" x-show="pagination.last_page > 1">
            <button @click="fetchCustomers(pagination.current_page - 1)" 
                    :disabled="pagination.current_page <= 1"
                    type="button" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
            <span class="px-2 font-medium text-slate-700" x-text="pagination.current_page + ' / ' + pagination.last_page"></span>
            <button @click="fetchCustomers(pagination.current_page + 1)" 
                    :disabled="pagination.current_page >= pagination.last_page"
                    type="button" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
        </div>
    </div>

    <!-- BEGIN: Modal Tambah / Edit Pelanggan -->
    <div x-show="modalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalOpen" 
                 @click="modalOpen = false" 
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <div x-show="modalOpen" 
                 class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900" x-text="isEditing ? 'Edit Data Pelanggan' : 'Tambah Pelanggan Baru'"></h3>
                    <button @click="modalOpen = false" type="button" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="saveCustomer()" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input x-model="form.full_name" required type="text" placeholder="Contoh: Budi Santoso" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                            <input x-model="form.phone" type="text" placeholder="Contoh: 081234567890" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                            <input x-model="form.email" type="email" placeholder="budi@example.com" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Domisili</label>
                        <textarea x-model="form.address" rows="2" placeholder="Alamat lengkap atau kelurahan / kota..." class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button @click="modalOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button :disabled="submitting" type="submit" class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-5 py-2.5 rounded-xl text-xs font-semibold shadow-xs transition-colors">
                            <span x-show="!submitting" x-text="isEditing ? 'Simpan Perubahan' : 'Tambah Pelanggan'"></span>
                            <span x-show="submitting">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Modal Tambah / Edit Pelanggan -->

    <!-- BEGIN: Modal Detail Pelanggan -->
    <div x-show="modalDetailOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalDetailOpen" 
                 @click="modalDetailOpen = false" 
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <div x-show="modalDetailOpen" 
                 class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" x-text="detailCustomer?.full_name"></h3>
                        <span class="text-xs text-slate-500" x-text="'Bergabung sejak ' + (detailCustomer?.joined_at || '-')"></span>
                    </div>
                    <button @click="modalDetailOpen = false" type="button" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <div>
                            <span class="text-slate-400 block">Total Belanja:</span>
                            <span class="text-sm font-bold text-slate-900" x-text="detailCustomer?.total_spent_formatted"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Total Transaksi:</span>
                            <span class="text-sm font-bold text-slate-900" x-text="(detailCustomer?.total_orders || 0) + 'x Transaksi'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Nomor HP / WA:</span>
                            <span class="font-mono font-semibold text-slate-800" x-text="detailCustomer?.phone"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Poin Loyalitas:</span>
                            <span class="font-bold text-amber-600" x-text="(detailCustomer?.points || 0) + ' Poin'"></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">Alamat:</span>
                        <p class="text-slate-700 bg-white border border-slate-100 p-2.5 rounded-xl" x-text="detailCustomer?.address || 'Belum ada alamat tercatat.'"></p>
                    </div>

                    <!-- Riwayat Transaksi Terakhir -->
                    <div>
                        <span class="font-bold text-slate-800 block mb-2 uppercase tracking-wide text-[11px]">Riwayat Pesanan Terakhir:</span>
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                                    <tr>
                                        <th class="py-2 px-3">No. Pesanan</th>
                                        <th class="py-2 px-3">Tanggal</th>
                                        <th class="py-2 px-3 text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="ord in detailCustomer?.recent_orders || []" :key="ord.id">
                                        <tr>
                                            <td class="py-2 px-3 font-mono font-bold text-slate-900" x-text="ord.order_number"></td>
                                            <td class="py-2 px-3 text-slate-500" x-text="ord.placed_at"></td>
                                            <td class="py-2 px-3 text-right font-semibold text-slate-900" x-text="ord.total_formatted"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="!detailCustomer?.recent_orders || detailCustomer.recent_orders.length === 0">
                                        <td colspan="3" class="py-4 text-center text-slate-400">Belum ada riwayat pesanan.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-slate-100 mt-5">
                    <button @click="modalDetailOpen = false" type="button" class="px-5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Modal Detail Pelanggan -->

    <!-- BEGIN: Modal Delete Confirmation -->
    <div x-show="modalDeleteOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalDeleteOpen" 
                 @click="modalDeleteOpen = false" 
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <div x-show="modalDeleteOpen" 
                 class="relative inline-block w-full max-w-sm p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                <div class="w-12 h-12 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Hapus Pelanggan Ini?</h3>
                <p class="text-xs text-slate-500 mt-2">
                    Apakah Anda yakin ingin menghapus data pelanggan <span class="font-bold text-slate-800" x-text="targetCustomer?.full_name"></span>? Data transaksi historis tetap tersimpan.
                </p>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button @click="modalDeleteOpen = false" type="button" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button @click="deleteCustomer()" :disabled="submitting" type="button" class="bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-xs transition-colors">
                        <span x-show="!submitting">Ya, Hapus</span>
                        <span x-show="submitting">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Modal Delete Confirmation -->

    <!-- BEGIN: Floating Toast -->
    <div x-show="toast.show" 
         x-cloak 
         x-transition
         class="fixed bottom-6 right-6 z-50 flex items-center gap-2.5 px-4 py-3 rounded-xl shadow-lg border text-xs font-semibold"
         :class="toast.type === 'error' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200'">
        <svg x-show="toast.type !== 'error'" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <svg x-show="toast.type === 'error'" class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        <span x-text="toast.message"></span>
    </div>
</main>

<script>
function customerManagement() {
    return {
        customers: [],
        metrics: { total_customers: 0, new_this_month: 0, vip_count: 0 },
        loading: false,
        submitting: false,
        searchQuery: '',
        selectedTier: '',
        pagination: { current_page: 1, last_page: 1, total: 0, from: 0, to: 0, per_page: 10 },
        modalOpen: false,
        modalDetailOpen: false,
        modalDeleteOpen: false,
        isEditing: false,
        targetCustomer: null,
        detailCustomer: null,
        form: {
            id: null,
            full_name: '',
            phone: '',
            email: '',
            address: '',
            status: 'active'
        },
        toast: { show: false, message: '', type: 'success' },

        init() {
            this.fetchCustomers(1);
        },

        async fetchCustomers(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: page,
                    search: this.searchQuery || '',
                    tier: this.selectedTier || ''
                });

                const res = await fetch(`/api/v1/operasional/pelanggan?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await res.json();

                if (result.success) {
                    this.customers = result.data || [];
                    this.metrics = result.metrics || this.metrics;
                    this.pagination = result.meta || this.pagination;
                } else {
                    this.showToast(result.message || 'Gagal memuat pelanggan.', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showToast('Gagal terhubung ke server.', 'error');
            } finally {
                this.loading = false;
            }
        },

        openCreateModal() {
            this.isEditing = false;
            this.form = {
                id: null,
                full_name: '',
                phone: '',
                email: '',
                address: '',
                status: 'active'
            };
            this.modalOpen = true;
        },

        openEditModal(cust) {
            this.isEditing = true;
            this.form = {
                id: cust.id,
                full_name: cust.full_name,
                phone: cust.phone === '-' ? '' : cust.phone,
                email: cust.email && !cust.email.startsWith('cust_') ? cust.email : '',
                address: cust.address || '',
                status: cust.status
            };
            this.modalOpen = true;
        },

        async openDetailModal(cust) {
            try {
                const res = await fetch(`/api/v1/operasional/pelanggan/${cust.id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await res.json();
                if (result.success) {
                    this.detailCustomer = result.data;
                    this.modalDetailOpen = true;
                }
            } catch (err) {
                this.showToast('Gagal memuat detail pelanggan.', 'error');
            }
        },

        async saveCustomer() {
            this.submitting = true;
            try {
                const url = this.isEditing 
                    ? `/api/v1/operasional/pelanggan/${this.form.id}`
                    : '/api/v1/operasional/pelanggan';
                const method = this.isEditing ? 'PUT' : 'POST';

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify(this.form)
                });

                const result = await res.json();

                if (res.ok && result.success) {
                    this.modalOpen = false;
                    this.showToast(result.message || 'Data pelanggan berhasil disimpan!');
                    this.fetchCustomers(this.isEditing ? this.pagination.current_page : 1);
                } else {
                    const errorMsg = result.errors ? Object.values(result.errors).flat().join(', ') : (result.message || 'Gagal menyimpan data.');
                    this.showToast(errorMsg, 'error');
                }
            } catch (err) {
                console.error(err);
                this.showToast('Gagal terhubung ke server.', 'error');
            } finally {
                this.submitting = false;
            }
        },

        confirmDelete(cust) {
            this.targetCustomer = cust;
            this.modalDeleteOpen = true;
        },

        async deleteCustomer() {
            if (!this.targetCustomer) return;

            this.submitting = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(`/api/v1/operasional/pelanggan/${this.targetCustomer.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                });
                const result = await res.json();

                if (result.success) {
                    this.modalDeleteOpen = false;
                    this.showToast('Pelanggan berhasil dihapus.');
                    this.fetchCustomers(1);
                } else {
                    this.showToast(result.message || 'Gagal menghapus.', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showToast('Terjadi kesalahan teknis.', 'error');
            } finally {
                this.submitting = false;
            }
        },

        exportCSV() {
            if (!this.customers.length) {
                this.showToast('Tidak ada data pelanggan untuk diekspor.', 'error');
                return;
            }

            let csv = 'Nama Lengkap,No Telepon,Email,Total Transaksi,Total Belanja (Rp),Poin,Status Member\n';
            this.customers.forEach(c => {
                csv += `"${c.full_name}","${c.phone}","${c.email}","${c.total_orders}","${c.total_spent}","${c.points}","${c.tier}"\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.setAttribute('download', `data_pelanggan_${new Date().toISOString().slice(0, 10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            this.showToast('Data pelanggan berhasil diunduh ke CSV!');
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message: message, type: type };
            setTimeout(() => {
                this.toast.show = false;
            }, 3500);
        }
    };
}
</script>
@endsection
