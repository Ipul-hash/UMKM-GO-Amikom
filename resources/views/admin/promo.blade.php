@extends('layouts.admin')

@section('title', 'UMKMGo - Promo & Diskon')

@section('content')
<main class="p-8 max-w-[1400px] w-full mx-auto" 
      data-purpose="promo-page" 
      x-data="promoManagement()" 
      x-init="init()">
    
    <!-- Top Title and CTA Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Promo &amp; Diskon</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola voucher, diskon persentase, dan program potongan harga toko.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="fetchPromos(pagination.current_page)" 
                    type="button" 
                    title="Muat ulang data"
                    class="px-3.5 py-2.5 text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" :class="loading ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Refresh</span>
            </button>
            <button @click="openCreateModal()" 
                    type="button" 
                    class="inline-flex items-center gap-2 bg-[#2563EB] hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span>Buat Promo Baru</span>
            </button>
        </div>
    </div>

    <!-- Search and Filter Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7">
        <!-- Search Input -->
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <input x-model.debounce.350ms="searchQuery" 
                   @input="fetchPromos(1)"
                   class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-xs" 
                   placeholder="Cari promo atau kode voucher..." 
                   type="text"/>
        </div>

        <!-- Filter Pills Container -->
        <div class="flex items-center bg-slate-100 p-1 rounded-xl gap-1 text-xs font-semibold overflow-x-auto no-scrollbar">
            <!-- All -->
            <button @click="setTab('all')" 
                    type="button" 
                    :class="activeTab === 'all' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 whitespace-nowrap">
                <span>Semua</span>
                <span class="text-[11px] opacity-75" x-text="'(' + counts.all + ')'"></span>
            </button>
            <!-- Active -->
            <button @click="setTab('active')" 
                    type="button" 
                    :class="activeTab === 'active' ? 'bg-white text-emerald-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 whitespace-nowrap">
                <span>Aktif</span>
                <span class="text-[11px] opacity-75" x-text="'(' + counts.active + ')'"></span>
            </button>
            <!-- Expired -->
            <button @click="setTab('expired')" 
                    type="button" 
                    :class="activeTab === 'expired' ? 'bg-white text-rose-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 whitespace-nowrap">
                <span>Berakhir</span>
                <span class="text-[11px] opacity-75" x-text="'(' + counts.expired + ')'"></span>
            </button>
            <!-- Disabled -->
            <button @click="setTab('disabled')" 
                    type="button" 
                    :class="activeTab === 'disabled' ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 whitespace-nowrap">
                <span>Nonaktif</span>
                <span class="text-[11px] opacity-75" x-text="'(' + counts.disabled + ')'"></span>
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="py-20 flex flex-col items-center justify-center text-slate-400">
        <svg class="animate-spin h-8 w-8 text-blue-600 mb-3" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <span class="text-xs font-medium">Memuat data promo &amp; diskon...</span>
    </div>

    <!-- Promo Cards Grid -->
    <div x-show="!loading && promos.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <template x-for="promo in promos" :key="promo.id">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs hover:border-blue-200 hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <!-- Header: Icon & Clean Status Text -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center font-extrabold text-lg select-none"
                             :class="promo.discount_type === 'percentage' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600'">
                            <span x-text="promo.discount_type === 'percentage' ? '%' : 'Rp'"></span>
                        </div>
                        
                        <!-- Clean Status Display (No Bullet Dot) -->
                        <span class="text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap"
                              :class="{
                                  'bg-emerald-50 text-emerald-600': promo.status === 'active',
                                  'bg-rose-50 text-rose-500': promo.status === 'expired',
                                  'bg-slate-100 text-slate-500': promo.status === 'disabled'
                              }"
                              x-text="promo.status_badge?.label"></span>
                    </div>

                    <!-- Card Title & Value -->
                    <h2 class="text-base font-bold text-slate-900 leading-snug mb-1" x-text="promo.name"></h2>
                    <p class="text-xs font-semibold text-blue-600 mb-3" x-text="'Potongan ' + promo.discount_formatted + (promo.max_discount_formatted ? ' (Maks ' + promo.max_discount_formatted + ')' : '')"></p>

                    <!-- Coupon Code Box with Click to Copy -->
                    <div @click="copyCode(promo.code)" 
                         title="Klik untuk menyalin kode voucher"
                         class="bg-slate-50/80 border border-dashed border-slate-200 hover:border-blue-300 rounded-xl p-3 mb-4 flex items-center justify-between cursor-pointer group transition-colors">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400">Kode:</span>
                            <span class="text-xs font-mono font-bold text-slate-800 tracking-wider group-hover:text-blue-600 transition-colors" x-text="promo.code"></span>
                        </div>
                        <span class="text-[11px] text-slate-400 group-hover:text-blue-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span>Salin</span>
                        </span>
                    </div>

                    <!-- Periode Info -->
                    <div class="mb-4">
                        <span class="text-[11px] text-slate-400 block mb-1">Periode Promo</span>
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-600">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
                                <line x1="16" x2="16" y1="2" y2="6"></line>
                                <line x1="8" x2="8" y1="2" y2="6"></line>
                                <line x1="3" x2="21" y1="10" y2="10"></line>
                            </svg>
                            <span x-text="promo.period_formatted"></span>
                        </div>
                    </div>

                    <!-- Kuota Progress -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center text-xs mb-1.5">
                            <span class="text-slate-400 font-medium">Penggunaan Kuota</span>
                            <span class="font-semibold text-slate-700" x-text="promo.quota_formatted"></span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all" 
                                 :class="promo.is_expired ? 'bg-slate-300' : 'bg-blue-600'"
                                 :style="'width: ' + promo.quota_percentage + '%;'"></div>
                        </div>
                    </div>

                    <!-- Min Purchase Info -->
                    <div class="text-[11px] text-slate-500 mb-2">
                        <span>Min. Belanja: </span>
                        <span class="font-semibold text-slate-700" x-text="promo.minimum_purchase_formatted"></span>
                    </div>
                </div>

                <!-- Bottom Footer: Toggle Switch & Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-2">
                    <!-- Toggle Switch -->
                    <div class="flex items-center gap-2.5">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   :checked="promo.is_active" 
                                   :disabled="promo.is_expired"
                                   @change="toggleStatus(promo)" 
                                   class="sr-only peer"/>
                            <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 peer-disabled:opacity-50"></div>
                        </label>
                        <span class="text-xs text-slate-500 font-medium select-none" x-text="promo.is_active ? 'Aktif' : 'Nonaktif'"></span>
                    </div>

                    <!-- Action Buttons: Edit & Delete -->
                    <div class="flex items-center gap-1.5">
                        <button @click="openEditModal(promo)" 
                                type="button" 
                                title="Edit Promo"
                                class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button @click="confirmDelete(promo)" 
                                type="button" 
                                title="Hapus Promo"
                                class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty Results State -->
    <div x-show="!loading && promos.length === 0" class="py-16 text-center bg-white rounded-2xl border border-slate-200">
        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
        </div>
        <h3 class="text-sm font-semibold text-slate-800">Tidak ada promo ditemukan</h3>
        <p class="text-xs text-slate-400 mt-1">Belum ada promo atau voucher yang sesuai dengan pencarian / filter ini.</p>
        <button @click="openCreateModal()" type="button" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition-colors">
            + Buat Promo Pertama
        </button>
    </div>

    <!-- BEGIN: PaginationFooter -->
    <div x-show="pagination.total > 0" class="flex items-center justify-between pt-4 text-xs text-slate-500">
        <p>Menampilkan <span class="font-medium text-slate-800" x-text="pagination.from || 0"></span> - <span class="font-medium text-slate-800" x-text="pagination.to || 0"></span> dari <span class="font-medium text-slate-800" x-text="pagination.total || 0"></span> promo</p>
        
        <div class="flex items-center gap-1.5 select-none" x-show="pagination.last_page > 1">
            <!-- Previous Button -->
            <button @click="fetchPromos(pagination.current_page - 1)" 
                    :disabled="pagination.current_page <= 1"
                    type="button" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
            
            <span class="px-2 font-medium text-slate-700" x-text="pagination.current_page + ' / ' + pagination.last_page"></span>
            
            <!-- Next Button -->
            <button @click="fetchPromos(pagination.current_page + 1)" 
                    :disabled="pagination.current_page >= pagination.last_page"
                    type="button" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
        </div>
    </div>

    <!-- BEGIN: Modal Create / Edit Promo -->
    <div x-show="modalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="modalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalOpen = false" 
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <div x-show="modalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900" x-text="isEditing ? 'Edit Promo &amp; Diskon' : 'Buat Promo Baru'"></h3>
                    <button @click="modalOpen = false" type="button" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="savePromo()" class="mt-4 space-y-4">
                    <!-- Nama Promo -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Promo <span class="text-rose-500">*</span></label>
                        <input x-model="form.name" required type="text" placeholder="Contoh: Diskon Akhir Pekan 20%" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                    </div>

                    <!-- Kode Voucher -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Voucher <span class="text-rose-500">*</span></label>
                        <input x-model="form.code" @input="form.code = form.code.toUpperCase()" required type="text" placeholder="Contoh: WEEKEND20" class="w-full font-mono uppercase text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                        <p class="text-[11px] text-slate-400 mt-1">Kode unik yang dimasukkan pelanggan saat transaksi kasir atau online checkout.</p>
                    </div>

                    <!-- Tipe Diskon & Nilai -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe Diskon <span class="text-rose-500">*</span></label>
                            <select x-model="form.discount_type" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500">
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Nominal Tetap (Rp)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Nilai Diskon <span class="text-rose-500">*</span>
                                <span class="text-slate-400 font-normal" x-text="form.discount_type === 'percentage' ? '(%)' : '(Rp)'"></span>
                            </label>
                            <input x-model.number="form.discount_value" required type="number" step="any" min="0.01" :placeholder="form.discount_type === 'percentage' ? '20' : '5000'" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                    <!-- Maksimal Potongan & Minimal Belanja -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Maksimal Diskon (Rp)</label>
                            <input x-model.number="form.max_discount_amount" type="number" step="1000" min="0" placeholder="Kosongkan jika tanpa batas" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Minimal Belanja (Rp)</label>
                            <input x-model.number="form.minimum_purchase" type="number" step="1000" min="0" placeholder="Contoh: 30000" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                    <!-- Kuota Pemakaian & Limit per User -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Total Kuota Pemakaian</label>
                            <input x-model.number="form.usage_limit" type="number" min="1" placeholder="Contoh: 100 (kosong = unlimited)" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Batas per Pelanggan</label>
                            <input x-model.number="form.per_user_limit" type="number" min="1" placeholder="Default: 1x pemakaian" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                    <!-- Tanggal Mulai & Selesai -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                            <input x-model="form.starts_at" type="datetime-local" class="w-full text-xs border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Berakhir</label>
                            <input x-model="form.ends_at" type="datetime-local" class="w-full text-xs border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan / Syarat Ketentuan</label>
                        <textarea x-model="form.description" rows="2" placeholder="Catatan internal atau syarat berlakunya promo..." class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button @click="modalOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button :disabled="submitting" type="submit" class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-5 py-2.5 rounded-xl text-xs font-semibold shadow-xs transition-colors flex items-center gap-1.5">
                            <span x-show="!submitting" x-text="isEditing ? 'Simpan Perubahan' : 'Buat Promo'"></span>
                            <span x-show="submitting">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Modal Create / Edit Promo -->

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
                <h3 class="text-base font-bold text-slate-900">Hapus Promo Ini?</h3>
                <p class="text-xs text-slate-500 mt-2">
                    Apakah Anda yakin ingin menghapus promo <span class="font-bold text-slate-700" x-text="deletePromoTarget?.name"></span> (<span class="font-mono text-slate-700" x-text="deletePromoTarget?.code"></span>)? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button @click="modalDeleteOpen = false" type="button" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button @click="deletePromo()" :disabled="submitting" type="button" class="bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-xs transition-colors">
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
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-3"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-2.5 px-4 py-3 rounded-xl shadow-lg border text-xs font-semibold"
         :class="toast.type === 'error' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200'">
        <svg x-show="toast.type !== 'error'" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <svg x-show="toast.type === 'error'" class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        <span x-text="toast.message"></span>
    </div>
</main>

<script>
function promoManagement() {
    return {
        promos: [],
        loading: false,
        submitting: false,
        searchQuery: '',
        activeTab: 'all',
        counts: { all: 0, active: 0, expired: 0, disabled: 0 },
        pagination: { current_page: 1, last_page: 1, total: 0, from: 0, to: 0, per_page: 9 },
        modalOpen: false,
        isEditing: false,
        modalDeleteOpen: false,
        deletePromoTarget: null,
        form: {
            id: null,
            name: '',
            code: '',
            discount_type: 'percentage',
            discount_value: '',
            max_discount_amount: '',
            minimum_purchase: 0,
            usage_limit: '',
            per_user_limit: 1,
            starts_at: '',
            ends_at: '',
            description: '',
            status: 'active'
        },
        toast: { show: false, message: '', type: 'success' },

        init() {
            this.fetchPromos(1);
        },

        async fetchPromos(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: page,
                    status: this.activeTab,
                    search: this.searchQuery || ''
                });

                const res = await fetch(`/api/v1/operasional/promo-diskon?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await res.json();

                if (result.success) {
                    this.promos = result.data || [];
                    this.counts = result.counts || this.counts;
                    this.pagination = result.meta || this.pagination;
                } else {
                    this.showToast(result.message || 'Gagal memuat promo.', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showToast('Terjadi kesalahan saat memuat data promo.', 'error');
            } finally {
                this.loading = false;
            }
        },

        setTab(tab) {
            this.activeTab = tab;
            this.fetchPromos(1);
        },

        openCreateModal() {
            this.isEditing = false;
            this.form = {
                id: null,
                name: '',
                code: '',
                discount_type: 'percentage',
                discount_value: '',
                max_discount_amount: '',
                minimum_purchase: 0,
                usage_limit: '',
                per_user_limit: 1,
                starts_at: '',
                ends_at: '',
                description: '',
                status: 'active'
            };
            this.modalOpen = true;
        },

        openEditModal(promo) {
            this.isEditing = true;
            this.form = {
                id: promo.id,
                name: promo.name,
                code: promo.code,
                discount_type: promo.discount_type,
                discount_value: promo.discount_value,
                max_discount_amount: promo.max_discount_amount || '',
                minimum_purchase: promo.minimum_purchase || 0,
                usage_limit: promo.usage_limit || '',
                per_user_limit: promo.per_user_limit || 1,
                starts_at: promo.starts_at || '',
                ends_at: promo.ends_at || '',
                description: promo.description || '',
                status: promo.status
            };
            this.modalOpen = true;
        },

        async savePromo() {
            this.submitting = true;
            try {
                const url = this.isEditing 
                    ? `/api/v1/operasional/promo-diskon/${this.form.id}`
                    : '/api/v1/operasional/promo-diskon';
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
                    this.showToast(result.message || 'Promo berhasil disimpan!');
                    this.fetchPromos(this.isEditing ? this.pagination.current_page : 1);
                } else {
                    const errorMsg = result.errors ? Object.values(result.errors).flat().join(', ') : (result.message || 'Gagal menyimpan promo.');
                    this.showToast(errorMsg, 'error');
                }
            } catch (err) {
                console.error(err);
                this.showToast('Gagal menghubungi server.', 'error');
            } finally {
                this.submitting = false;
            }
        },

        async toggleStatus(promo) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(`/api/v1/operasional/promo-diskon/${promo.id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                });
                const result = await res.json();

                if (result.success) {
                    promo.status = result.data.status;
                    promo.is_active = result.data.is_active;
                    promo.status_badge = {
                        label: promo.is_active ? 'Aktif' : 'Nonaktif',
                        color: promo.is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500'
                    };
                    this.showToast(`Status voucher ${promo.code} berhasil diubah!`);
                    this.fetchPromos(this.pagination.current_page);
                } else {
                    this.showToast(result.message || 'Gagal mengubah status.', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showToast('Terjadi kesalahan teknis.', 'error');
            }
        },

        confirmDelete(promo) {
            this.deletePromoTarget = promo;
            this.modalDeleteOpen = true;
        },

        async deletePromo() {
            if (!this.deletePromoTarget) return;

            this.submitting = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(`/api/v1/operasional/promo-diskon/${this.deletePromoTarget.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                });
                const result = await res.json();

                if (result.success) {
                    this.modalDeleteOpen = false;
                    this.showToast('Promo berhasil dihapus.');
                    this.fetchPromos(1);
                } else {
                    this.showToast(result.message || 'Gagal menghapus promo.', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showToast('Gagal menghubungi server.', 'error');
            } finally {
                this.submitting = false;
            }
        },

        copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                this.showToast(`Kode "${code}" berhasil disalin ke clipboard!`);
            }).catch(() => {
                this.showToast(`Kode voucher: ${code}`);
            });
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
