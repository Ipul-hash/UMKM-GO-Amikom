@extends('layouts.admin')

@section('title', 'UMKMGo - Supplier / Pemasok')

@section('content')
<main class="p-8 max-w-[1400px] w-full mx-auto" 
      data-purpose="suppliers-page"
      x-data="supplierManagement()" 
      x-init="init()">
    
    <!-- Header Section: Title & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Supplier / Pemasok</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar vendor penyedia bahan baku, produk, dan kemasan toko.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="fetchSuppliers(pagination.current_page)" 
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
                    class="inline-flex items-center gap-2 bg-[#2563EB] hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-xs transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span>Tambah Supplier</span>
            </button>
        </div>
    </div>

    <!-- Filter & Search Bar Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <!-- Search Input Box -->
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" x2="16.65" y1="21" y2="16.65"></line>
                </svg>
            </span>
            <input x-model.debounce.350ms="searchQuery" 
                   @input="fetchSuppliers(1)"
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white border border-slate-200 text-xs sm:text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-xs transition-all" 
                   placeholder="Cari nama vendor, PIC, kota..." 
                   type="text"/>
        </div>

        <!-- Category Filter & Counter -->
        <div class="flex items-center gap-3">
            <div class="relative min-w-[170px]" x-show="categories.length > 0">
                <select x-model="selectedCategory" 
                        @change="fetchSuppliers(1)"
                        class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer pr-9 shadow-xs">
                    <option value="">Semua Kategori Pasokan</option>
                    <template x-for="cat in categories" :key="cat">
                        <option :value="cat" x-text="cat"></option>
                    </template>
                </select>
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </span>
            </div>

            <div class="text-xs text-slate-500 font-semibold bg-slate-100 px-3.5 py-2 rounded-xl" x-text="pagination.total + ' Supplier'"></div>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="py-20 flex flex-col items-center justify-center text-slate-400">
        <svg class="animate-spin h-8 w-8 text-blue-600 mb-3" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <span class="text-xs font-medium">Memuat data supplier...</span>
    </div>

    <!-- BEGIN: SupplierCardsGrid -->
    <div x-show="!loading && suppliers.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <template x-for="sup in suppliers" :key="sup.id">
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:border-blue-200 hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <!-- Header info with Category Icon & Actions -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3.5">
                            <!-- Category-specific Icon -->
                            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 text-blue-600 border border-blue-100">
                                <!-- Coffee Icon -->
                                <template x-if="sup.icon_type === 'coffee'">
                                    <svg class="w-5 h-5 stroke-[1.8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 8h1a4 4 0 0 1 0 8h-1" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <line x1="6" x2="6" y1="1" y2="4"></line>
                                        <line x1="10" x2="10" y1="1" y2="4"></line>
                                        <line x1="14" x2="14" y1="1" y2="4"></line>
                                    </svg>
                                </template>

                                <!-- Package Icon -->
                                <template x-if="sup.icon_type === 'package'">
                                    <svg class="w-5 h-5 stroke-[1.8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" x2="12" y1="22.08" y2="12"></line>
                                    </svg>
                                </template>

                                <!-- Apparel Icon -->
                                <template x-if="sup.icon_type === 'apparel'">
                                    <svg class="w-5 h-5 stroke-[1.8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </template>

                                <!-- General Icon -->
                                <template x-if="sup.icon_type === 'general'">
                                    <svg class="w-5 h-5 stroke-[1.8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 21h18M3 7v14M21 7v14M6 11h2M6 15h2M16 11h2M16 15h2M9 21V9h6v12M9 3h6l2 4H7l2-4z" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </template>
                            </div>

                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-slate-900 leading-snug truncate" x-text="sup.name"></h3>
                                <p class="text-xs text-blue-600 font-semibold mt-0.5" x-text="'Pasokan: ' + sup.supply_category"></p>
                            </div>
                        </div>

                        <!-- Action Menu (Edit / Delete) -->
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button @click="openEditModal(sup)" 
                                    type="button" 
                                    title="Edit Supplier"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button @click="confirmDelete(sup)" 
                                    type="button" 
                                    title="Hapus Supplier"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Thin divider -->
                    <div class="border-t border-slate-100 my-4"></div>

                    <!-- Contact & PIC details -->
                    <div class="space-y-2.5 text-xs">
                        <div class="text-[11px] font-medium text-slate-400">PIC / Penanggung Jawab</div>
                        
                        <!-- PIC Name -->
                        <div class="flex items-center gap-2.5 text-slate-700">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                <circle cx="12" cy="7" r="4" stroke-width="1.8"></circle>
                            </svg>
                            <span class="font-bold text-slate-800" x-text="sup.pic_name"></span>
                        </div>

                        <!-- Phone / WhatsApp Link -->
                        <div class="flex items-center justify-between text-slate-600">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                </svg>
                                <span class="font-mono text-slate-700 font-medium" x-text="sup.phone"></span>
                            </div>
                            <a :href="sup.whatsapp_url" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               title="Chat WhatsApp"
                               class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 hover:underline">
                                <span>WhatsApp</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>

                        <!-- City / Address -->
                        <div class="flex items-center gap-2.5 text-slate-600">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                <circle cx="12" cy="10" r="3" stroke-width="1.8"></circle>
                            </svg>
                            <span class="truncate" x-text="sup.city + (sup.address ? ' - ' + sup.address : '')"></span>
                        </div>
                    </div>
                </div>

                <!-- Action Button: Buat PO -->
                <button @click="openPoModal(sup)" 
                        type="button" 
                        class="mt-5 w-full py-2.5 px-3 border border-blue-600 text-blue-600 hover:bg-blue-50 active:bg-blue-100 rounded-xl text-xs font-semibold flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-linecap="round" stroke-linejoin="round"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" x2="12" y1="18" y2="12"></line>
                        <line x1="9" x2="15" y1="15" y2="15"></line>
                    </svg>
                    <span>Buat PO / Catatan Pengadaan</span>
                </button>
            </div>
        </template>
    </div>

    <!-- Empty Results State -->
    <div x-show="!loading && suppliers.length === 0" class="py-16 text-center bg-white rounded-2xl border border-slate-200">
        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <h3 class="text-sm font-semibold text-slate-800">Tidak ada supplier ditemukan</h3>
        <p class="text-xs text-slate-400 mt-1">Belum ada data vendor yang cocok dengan pencarian.</p>
        <button @click="openCreateModal()" type="button" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs transition-colors">
            + Tambah Supplier Pertama
        </button>
    </div>

    <!-- BEGIN: PaginationFooter -->
    <div x-show="pagination.total > 0" class="flex items-center justify-between pt-2 text-xs text-slate-500">
        <p>Menampilkan <span class="font-medium text-slate-800" x-text="pagination.from || 0"></span> - <span class="font-medium text-slate-800" x-text="pagination.to || 0"></span> dari <span class="font-medium text-slate-800" x-text="pagination.total || 0"></span> supplier</p>
        
        <div class="flex items-center gap-1.5 select-none" x-show="pagination.last_page > 1">
            <button @click="fetchSuppliers(pagination.current_page - 1)" 
                    :disabled="pagination.current_page <= 1"
                    type="button" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
            <span class="px-2 font-medium text-slate-700" x-text="pagination.current_page + ' / ' + pagination.last_page"></span>
            <button @click="fetchSuppliers(pagination.current_page + 1)" 
                    :disabled="pagination.current_page >= pagination.last_page"
                    type="button" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
        </div>
    </div>

    <!-- BEGIN: Modal Tambah / Edit Supplier -->
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
                    <h3 class="text-base font-bold text-slate-900" x-text="isEditing ? 'Edit Data Supplier' : 'Tambah Supplier Baru'"></h3>
                    <button @click="modalOpen = false" type="button" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="saveSupplier()" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Perusahaan / Supplier <span class="text-rose-500">*</span></label>
                        <input x-model="form.name" required type="text" placeholder="Contoh: PT Biji Kopi Nusantara" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Pasokan <span class="text-rose-500">*</span></label>
                            <input x-model="form.supply_category" required type="text" placeholder="Contoh: Biji Kopi, Kemasan, Sirup" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama PIC / Kontak <span class="text-rose-500">*</span></label>
                            <input x-model="form.pic_name" required type="text" placeholder="Contoh: Pak Haryanto" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon / WA <span class="text-rose-500">*</span></label>
                            <input x-model="form.phone" required type="text" placeholder="Contoh: 081299887766" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email Vendor</label>
                            <input x-model="form.email" type="email" placeholder="sales@vendor.com" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kota</label>
                            <input x-model="form.city" type="text" placeholder="Contoh: Bandung" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                            <select x-model="form.status" class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500">
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Gudang / Kantor</label>
                        <textarea x-model="form.address" rows="2" placeholder="Alamat lengkap supplier..." class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button @click="modalOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button :disabled="submitting" type="submit" class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-5 py-2.5 rounded-xl text-xs font-semibold shadow-xs transition-colors">
                            <span x-show="!submitting" x-text="isEditing ? 'Simpan Perubahan' : 'Tambah Supplier'"></span>
                            <span x-show="submitting">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Modal Tambah / Edit Supplier -->

    <!-- BEGIN: Modal Buat PO / Chat WhatsApp -->
    <div x-show="modalPoOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalPoOpen" 
                 @click="modalPoOpen = false" 
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

            <div x-show="modalPoOpen" 
                 class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900" x-text="'Buat Purchase Order (PO) - ' + (poSupplier?.name || '')"></h3>
                        <p class="text-xs text-slate-500 mt-0.5" x-text="'Kontak: ' + (poSupplier?.pic_name || '') + ' (' + (poSupplier?.phone || '') + ')'"></p>
                    </div>
                    <button @click="modalPoOpen = false" type="button" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-3.5 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Daftar Barang / Pasokan yang Dipesan:</label>
                        <textarea x-model="poNotes" rows="4" placeholder="Contoh:&#10;- Biji Kopi Arabika 10 Kg&#10;- Biji Kopi Robusta 5 Kg&#10;Mohon dikirim paling lambat hari Jumat." class="w-full text-xs border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 font-sans"></textarea>
                    </div>

                    <div class="bg-blue-50/70 border border-blue-100 p-3 rounded-xl text-blue-900 text-[11.5px] leading-relaxed">
                        Anda dapat langsung mengirim format pesanan PO ini ke WhatsApp PIC vendor dengan tombol di bawah.
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 mt-5">
                    <button @click="modalPoOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Tutup
                    </button>
                    <button @click="sendPoWhatsApp()" type="button" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold shadow-xs transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Kirim Pesanan ke WhatsApp</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Modal Buat PO -->

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
                <h3 class="text-base font-bold text-slate-900">Hapus Supplier Ini?</h3>
                <p class="text-xs text-slate-500 mt-2">
                    Apakah Anda yakin ingin menghapus vendor <span class="font-bold text-slate-800" x-text="targetSupplier?.name"></span>? Data ini tidak dapat dikembalikan.
                </p>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button @click="modalDeleteOpen = false" type="button" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button @click="deleteSupplier()" :disabled="submitting" type="button" class="bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-xs transition-colors">
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
function supplierManagement() {
    return {
        suppliers: [],
        categories: [],
        loading: false,
        submitting: false,
        searchQuery: '',
        selectedCategory: '',
        pagination: { current_page: 1, last_page: 1, total: 0, from: 0, to: 0, per_page: 9 },
        modalOpen: false,
        modalDeleteOpen: false,
        modalPoOpen: false,
        isEditing: false,
        targetSupplier: null,
        poSupplier: null,
        poNotes: '',
        form: {
            id: null,
            name: '',
            supply_category: '',
            pic_name: '',
            phone: '',
            email: '',
            city: '',
            address: '',
            notes: '',
            status: 'active'
        },
        toast: { show: false, message: '', type: 'success' },

        init() {
            this.fetchSuppliers(1);
        },

        async fetchSuppliers(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: page,
                    search: this.searchQuery || '',
                    category: this.selectedCategory || ''
                });

                const res = await fetch(`/api/v1/operasional/supplier?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await res.json();

                if (result.success) {
                    this.suppliers = result.data || [];
                    this.categories = result.categories || this.categories;
                    this.pagination = result.meta || this.pagination;
                } else {
                    this.showToast(result.message || 'Gagal memuat supplier.', 'error');
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
                name: '',
                supply_category: '',
                pic_name: '',
                phone: '',
                email: '',
                city: '',
                address: '',
                notes: '',
                status: 'active'
            };
            this.modalOpen = true;
        },

        openEditModal(sup) {
            this.isEditing = true;
            this.form = {
                id: sup.id,
                name: sup.name,
                supply_category: sup.supply_category,
                pic_name: sup.pic_name,
                phone: sup.phone,
                email: sup.email === '-' ? '' : sup.email,
                city: sup.city === 'Indonesia' ? '' : sup.city,
                address: sup.address || '',
                notes: sup.notes || '',
                status: sup.status
            };
            this.modalOpen = true;
        },

        openPoModal(sup) {
            this.poSupplier = sup;
            this.poNotes = `Halo ${sup.pic_name} dari ${sup.name},\nSaya ingin membuat Purchase Order (PO) pengadaan pasokan ${sup.supply_category}:\n\n- [Nama Barang 1]: ... Qty\n- [Nama Barang 2]: ... Qty\n\nMohon konfirmasi ketersediaan stok & total tagihannya. Terima kasih!`;
            this.modalPoOpen = true;
        },

        sendPoWhatsApp() {
            if (!this.poSupplier) return;
            const text = encodeURIComponent(this.poNotes);
            const cleanPhone = this.poSupplier.phone.replace(/[^0-9]/g, '');
            const waNumber = cleanPhone.startsWith('0') ? '62' + cleanPhone.slice(1) : cleanPhone;
            window.open(`https://wa.me/${waNumber}?text=${text}`, '_blank');
            this.modalPoOpen = false;
            this.showToast('Membuka WhatsApp untuk mengirim PO...');
        },

        async saveSupplier() {
            this.submitting = true;
            try {
                const url = this.isEditing 
                    ? `/api/v1/operasional/supplier/${this.form.id}`
                    : '/api/v1/operasional/supplier';
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
                    this.showToast(result.message || 'Data supplier berhasil disimpan!');
                    this.fetchSuppliers(this.isEditing ? this.pagination.current_page : 1);
                } else {
                    const errorMsg = result.errors ? Object.values(result.errors).flat().join(', ') : (result.message || 'Gagal menyimpan supplier.');
                    this.showToast(errorMsg, 'error');
                }
            } catch (err) {
                console.error(err);
                this.showToast('Gagal terhubung ke server.', 'error');
            } finally {
                this.submitting = false;
            }
        },

        confirmDelete(sup) {
            this.targetSupplier = sup;
            this.modalDeleteOpen = true;
        },

        async deleteSupplier() {
            if (!this.targetSupplier) return;

            this.submitting = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(`/api/v1/operasional/supplier/${this.targetSupplier.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                });
                const result = await res.json();

                if (result.success) {
                    this.modalDeleteOpen = false;
                    this.showToast('Supplier berhasil dihapus.');
                    this.fetchSuppliers(1);
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
