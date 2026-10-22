@extends('layouts.admin')

@section('title', 'UMKMGo - Manajemen Karyawan')

@section('content')
<main class="flex-1 p-8" x-data="karyawanManagement()" x-init="init()">
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

    <!-- Page Title & Primary Action -->
    <div class="flex items-center justify-between mb-7">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Karyawan</h1>
            <p class="text-[13px] text-slate-500 mt-1">Kelola staf, hak akses, dan jadwal shift</p>
        </div>
        <button @click="openAddModal()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-[13px] px-4 py-2.5 rounded-xl shadow-sm transition-all duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
            </svg>
            Tambah Karyawan
        </button>
    </div>

    <!-- KPI Summary Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-7">
        <!-- Card 1: Total Karyawan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 flex justify-between items-start shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <div class="flex flex-col">
                <span class="text-[13px] font-medium text-slate-500">Total Karyawan</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900 tracking-tight" x-text="summary.total_employees">0</span>
                    <span class="text-sm font-medium text-slate-500">Orang</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50/80 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                </svg>
            </div>
        </div>

        <!-- Card 2: Shift Hari Ini -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 flex justify-between items-start shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <div class="flex flex-col">
                <span class="text-[13px] font-medium text-slate-500">Shift Hari Ini</span>
                <div class="mt-4 flex items-center gap-7">
                    <!-- Shift Pagi -->
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span class="text-[13px] text-slate-600">Pagi:</span>
                        <span class="text-xl font-bold text-slate-900 ml-0.5" x-text="summary.shift_pagi">0</span>
                    </div>
                    <div class="w-px h-5 bg-slate-200"></div>
                    <!-- Shift Malam -->
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span class="text-[13px] text-slate-600">Malam:</span>
                        <span class="text-xl font-bold text-slate-900 ml-0.5" x-text="summary.shift_malam">0</span>
                    </div>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50/80 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Data Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <!-- Table Header & Controls -->
        <div class="p-6 pb-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">Daftar Karyawan</h2>
                <p class="text-[13px] text-slate-500 mt-0.5">Informasi staf dan pembagian shift di outlet Anda.</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Search Input -->
                <div class="relative w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </span>
                    <input type="text"
                           x-model="search"
                           @input.debounce.300ms="fetchEmployees()"
                           class="w-full pl-9 pr-3.5 py-2 text-[13px] rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"
                           placeholder="Cari nama karyawan..."/>
                </div>

                <!-- Filter Status Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 text-[13px] font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span x-text="filterStatus === 'all' ? 'Semua Status' : (filterStatus === 'active' ? 'Aktif' : 'Nonaktif')">Semua Status</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-20 text-[13px]"
                         style="display: none;">
                        <button @click="filterStatus = 'all'; open = false; fetchEmployees()" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="filterStatus === 'all' ? 'font-semibold text-blue-600' : 'text-slate-700'">Semua Status</button>
                        <button @click="filterStatus = 'active'; open = false; fetchEmployees()" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="filterStatus === 'active' ? 'font-semibold text-blue-600' : 'text-slate-700'">Aktif</button>
                        <button @click="filterStatus = 'inactive'; open = false; fetchEmployees()" class="w-full text-left px-4 py-2 hover:bg-slate-50" :class="filterStatus === 'inactive' ? 'font-semibold text-blue-600' : 'text-slate-700'">Nonaktif</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto min-w-[760px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-t border-b border-slate-100 bg-[#fafafa]/50 text-[11px] font-medium text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-6">Nama Karyawan</th>
                        <th class="py-3 px-6">Peran / Jabatan</th>
                        <th class="py-3 px-6">Hak Akses (RBAC)</th>
                        <th class="py-3 px-6">Shift Kerja</th>
                        <th class="py-3 px-6">No. WhatsApp</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[13px]">
                    <template x-for="emp in employees" :key="emp.id">
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-semibold text-xs flex items-center justify-center flex-shrink-0"
                                         x-text="emp.initials">
                                        BP
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900" x-text="emp.name">Nama</div>
                                        <div class="text-[11px] text-slate-400" x-text="emp.email">email@umkmgo.id</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-slate-600 font-normal" x-text="emp.role">
                                Kasir Utama
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-xs">
                                <template x-if="emp.role_key === 'seller_owner'">
                                    <span class="font-bold text-blue-600">Full Access</span>
                                </template>
                                <template x-if="emp.role_key !== 'seller_owner'">
                                    <span class="font-medium text-slate-700" x-text="(emp.permissions ? emp.permissions.length : 0) + ' Hak Akses'"></span>
                                </template>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-medium" x-text="emp.shift">Shift Pagi</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-slate-600" x-text="emp.phone">
                                0812-1111-2222
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                      :class="emp.is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500'"
                                      x-text="emp.status_label">
                                    Aktif
                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-xs font-semibold">
                                <div class="flex items-center gap-3">
                                    <button @click="openShiftModal(emp)" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-900 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                        Shift
                                    </button>
                                    <button @click="openEditModal(emp)" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button x-show="emp.role_key !== 'seller_owner'" @click="deleteEmployee(emp)" class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="employees.length === 0">
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            Tidak ada karyawan ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="px-6 py-4 flex items-center justify-between border-t border-slate-100">
            <p class="text-[13px] text-slate-500">
                Menampilkan <span class="font-bold text-slate-700" x-text="employees.length">0</span> karyawan
            </p>
        </div>
    </div>

    <!-- Modal Form: Tambah / Edit Karyawan -->
    <div x-show="showFormModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40"
         style="display: none;">
        <div @click.away="showFormModal = false" class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-shrink-0">
                <div>
                    <h3 class="text-base font-bold text-slate-900" x-text="isEditing ? 'Edit Karyawan' : 'Tambah Karyawan Baru'"></h3>
                    <p class="text-xs text-slate-500 mt-0.5">Isi biodata staf dan atur hak akses custom per fitur</p>
                </div>
                <button @click="showFormModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </button>
            </div>

            <form @submit.prevent="saveEmployee()" class="space-y-4 mt-4 overflow-y-auto pr-1 flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" x-model="form.full_name" required class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Contoh: Budi Pratama">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email *</label>
                        <input type="email" x-model="form.email" required class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="budi@umkmgo.id">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp</label>
                        <input type="text" x-model="form.phone" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="081234567890">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Peran / Jabatan *</label>
                        <select x-model="form.role" @change="onRoleChange()" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="cashier">Kasir Utama</option>
                            <option value="seller_staff">Staf Operasional</option>
                            <option value="manager">Manajer Toko</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Shift Kerja</label>
                        <select x-model="form.shift" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="pagi">Pagi (08:00 - 16:00)</option>
                            <option value="malam">Malam (16:00 - 24:00)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div x-show="!isEditing">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Password Akses</label>
                        <input type="password" x-model="form.password" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Minimal 6 karakter">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status Karyawan</label>
                        <select x-model="form.is_active" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option :value="true">Aktif</option>
                            <option :value="false">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <!-- Custom Permission Matrix (RBAC) -->
                <div class="pt-4 border-t border-slate-200">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-900">Custom Permission Matrix (Hak Akses Granular)</label>
                            <p class="text-[11px] text-slate-500">Centang izin fitur sesuai alur panel admin UMKM GO</p>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200" x-text="form.permissions.length + ' Izin Aktif'"></span>
                    </div>

                    <!-- Preset Pills -->
                    <div class="flex flex-wrap items-center gap-1.5 mb-3 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <span class="text-[11px] font-bold text-slate-500 mr-1">Preset:</span>
                        <button type="button" @click="applyPreset('cashier')" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 transition-colors">
                            Kasir
                        </button>
                        <button type="button" @click="applyPreset('seller_staff')" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 transition-colors">
                            Staf Gudang
                        </button>
                        <button type="button" @click="applyPreset('manager')" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 transition-colors">
                            Manajer
                        </button>
                        <button type="button" @click="selectAllPermissions()" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-blue-600 text-white hover:bg-blue-700 transition-colors ml-auto">
                            Pilih Semua
                        </button>
                        <button type="button" @click="clearPermissions()" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-200 text-slate-700 hover:bg-slate-300 transition-colors">
                            Kosongkan
                        </button>
                    </div>

                    <!-- Matrix Checkboxes Grouped by 3 Flows -->
                    <div class="space-y-3">
                        <!-- Flow 1 -->
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    Flow 1: Manajemen Produk & Stok
                                </span>
                                <span class="text-[10px] text-slate-400">Katalog, Varian, Inventaris</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('products.view')" @change="togglePermission('products.view')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Lihat Daftar Produk</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('products.manage')" @change="togglePermission('products.manage')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Tambah & Edit Produk</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('products.delete')" @change="togglePermission('products.delete')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Hapus Produk</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('inventory.view')" @change="togglePermission('inventory.view')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Lihat Stok & Mutasi</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('inventory.manage')" @change="togglePermission('inventory.manage')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Penyesuaian Stok (Opname)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Flow 2 -->
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                    Flow 2: Operasional Pesanan & Pengiriman
                                </span>
                                <span class="text-[10px] text-slate-400">Kasir, Pesanan Masuk, Ekspedisi</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('pos.access')" @change="togglePermission('pos.access')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Akses POS / Kasir</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('orders.view')" @change="togglePermission('orders.view')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Lihat Pesanan Masuk</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('orders.process')" @change="togglePermission('orders.process')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Proses & Siapkan Barang</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('orders.ship')" @change="togglePermission('orders.ship')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Atur Pengiriman & Resi</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('customers.view')" @change="togglePermission('customers.view')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Lihat Data Pelanggan</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('customers.manage')" @change="togglePermission('customers.manage')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Kelola Data Pelanggan</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('promo.manage')" @change="togglePermission('promo.manage')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Kelola Promo & Diskon</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('suppliers.manage')" @change="togglePermission('suppliers.manage')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Kelola Mitra Supplier</span>
                                </label>
                            </div>
                        </div>

                        <!-- Flow 3 -->
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                    Flow 3: Penjualan & Laporan Toko
                                </span>
                                <span class="text-[10px] text-slate-400">Analitik, Buku Kas, Pengaturan</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('reports.view')" @change="togglePermission('reports.view')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Laporan Penjualan & Analitik</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('finance.view')" @change="togglePermission('finance.view')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Lihat Buku Kas & Keuangan</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('finance.manage')" @change="togglePermission('finance.manage')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Kelola Transaksi Kas</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('employees.manage')" @change="togglePermission('employees.manage')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Kelola Karyawan & RBAC</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded hover:bg-white transition-colors">
                                    <input type="checkbox" :checked="form.permissions.includes('settings.manage')" @change="togglePermission('settings.manage')" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>Pengaturan Toko & Profil</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 flex-shrink-0">
                    <button type="button" @click="showFormModal = false" class="px-4 py-2 text-[13px] font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" :disabled="loading" class="px-5 py-2 text-[13px] font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm disabled:opacity-50">
                        <span x-text="loading ? 'Menyimpan...' : 'Simpan Karyawan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Form: Atur Shift Kerja -->
    <div x-show="showShiftModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/40"
         style="display: none;">
        <div @click.away="showShiftModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Atur Shift Kerja</h3>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="selectedEmployee ? selectedEmployee.name : ''"></p>
                </div>
                <button @click="showShiftModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </button>
            </div>

            <form @submit.prevent="saveShift()" class="space-y-4 mt-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Jadwal Shift</label>
                    <select x-model="shiftForm.shift" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="pagi">Shift Pagi (08:00 - 16:00)</option>
                        <option value="malam">Shift Malam (16:00 - 24:00)</option>
                    </select>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="shiftForm.open_pos_shift" class="rounded text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-semibold text-slate-700">Buka Sesi Kasir POS Sekarang</span>
                    </label>
                </div>

                <div x-show="shiftForm.open_pos_shift" class="space-y-3 pl-6 border-l-2 border-blue-200" style="display: none;">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Register / Mesin Kasir</label>
                        <input type="text" x-model="shiftForm.register_name" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl" placeholder="Kasir Utama #1">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kas Awal (Rp)</label>
                        <input type="number" x-model="shiftForm.opening_cash" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan</label>
                        <input type="text" x-model="shiftForm.note" class="w-full px-3 py-2 text-[13px] border border-slate-200 rounded-xl" placeholder="Opsional">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" @click="showShiftModal = false" class="px-4 py-2 text-[13px] font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" :disabled="loading" class="px-5 py-2 text-[13px] font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm disabled:opacity-50">
                        <span x-text="loading ? 'Menyimpan...' : 'Simpan Shift'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function karyawanManagement() {
    return {
        employees: [],
        summary: {
            total_employees: 0,
            shift_pagi: 0,
            shift_malam: 0,
            active_shifts: 0
        },
        search: '',
        filterStatus: 'all',
        loading: false,
        showFormModal: false,
        isEditing: false,
        showShiftModal: false,
        selectedEmployee: null,
        form: {
            id: null,
            full_name: '',
            email: '',
            phone: '',
            role: 'cashier',
            shift: 'pagi',
            is_active: true,
            permissions: [],
            password: 'password123'
        },
        presets: {
            cashier: [
                'pos.access', 'orders.view', 'customers.view', 'customers.manage', 'promo.manage'
            ],
            seller_staff: [
                'products.view', 'products.manage', 'inventory.view', 'inventory.manage', 'orders.view', 'orders.process', 'orders.ship', 'suppliers.manage'
            ],
            manager: [
                'products.view', 'products.manage', 'inventory.view', 'inventory.manage', 'pos.access', 'orders.view', 'orders.process', 'orders.ship', 'customers.view', 'customers.manage', 'promo.manage', 'suppliers.manage', 'reports.view', 'finance.view'
            ]
        },
        allPermissions: [
            'products.view', 'products.manage', 'products.delete', 'inventory.view', 'inventory.manage',
            'pos.access', 'orders.view', 'orders.process', 'orders.ship', 'customers.view', 'customers.manage', 'promo.manage', 'suppliers.manage',
            'reports.view', 'finance.view', 'finance.manage', 'employees.manage', 'settings.manage'
        ],
        onRoleChange() {
            if (this.presets[this.form.role]) {
                this.form.permissions = [...this.presets[this.form.role]];
            }
        },
        applyPreset(role) {
            if (this.presets[role]) {
                this.form.permissions = [...this.presets[role]];
            }
        },
        selectAllPermissions() {
            this.form.permissions = [...this.allPermissions];
        },
        clearPermissions() {
            this.form.permissions = [];
        },
        togglePermission(perm) {
            const idx = this.form.permissions.indexOf(perm);
            if (idx > -1) {
                this.form.permissions.splice(idx, 1);
            } else {
                this.form.permissions.push(perm);
            }
        },
        shiftForm: {
            shift: 'pagi',
            open_pos_shift: false,
            register_name: 'Kasir Utama #1',
            opening_cash: 200000,
            note: ''
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
        async init() {
            await this.fetchEmployees();
        },
        async fetchEmployees() {
            this.loading = true;
            try {
                let url = '/api/v1/operasional/karyawan?';
                if (this.search) url += `search=${encodeURIComponent(this.search)}&`;
                if (this.filterStatus !== 'all') url += `status=${encodeURIComponent(this.filterStatus)}&`;
                
                const res = await fetch(url);
                const json = await res.json();
                if (json.success) {
                    this.employees = json.data;
                    this.summary = json.summary;
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Gagal memuat data karyawan', 'error');
            } finally {
                this.loading = false;
            }
        },
        openAddModal() {
            this.isEditing = false;
            this.form = {
                id: null,
                full_name: '',
                email: '',
                phone: '',
                role: 'cashier',
                shift: 'pagi',
                is_active: true,
                permissions: [...this.presets.cashier],
                password: 'password123'
            };
            this.showFormModal = true;
        },
        openEditModal(emp) {
            this.isEditing = true;
            this.form = {
                id: emp.id,
                full_name: emp.name,
                email: emp.email,
                phone: emp.phone === '-' ? '' : emp.phone,
                role: emp.role_key,
                shift: emp.shift_key,
                is_active: emp.is_active,
                permissions: emp.permissions ? [...emp.permissions] : [...(this.presets[emp.role_key] || [])],
                password: ''
            };
            this.showFormModal = true;
        },
        openShiftModal(emp) {
            this.selectedEmployee = emp;
            this.shiftForm = {
                shift: emp.shift_key,
                open_pos_shift: false,
                register_name: 'Kasir Utama #1',
                opening_cash: 200000,
                note: ''
            };
            this.showShiftModal = true;
        },
        async saveEmployee() {
            this.loading = true;
            try {
                const url = this.isEditing 
                    ? `/api/v1/operasional/karyawan/${this.form.id}`
                    : '/api/v1/operasional/karyawan';
                const method = this.isEditing ? 'PUT' : 'POST';

                const res = await fetch(url, {
                    method: method,
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
                    this.showFormModal = false;
                    await this.fetchEmployees();
                } else {
                    this.showNotification(json.message || 'Gagal menyimpan data', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Terjadi kesalahan sistem', 'error');
            } finally {
                this.loading = false;
            }
        },
        async saveShift() {
            if (!this.selectedEmployee) return;
            this.loading = true;
            try {
                const res = await fetch(`/api/v1/operasional/karyawan/${this.selectedEmployee.id}/assign-shift`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.shiftForm)
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.showNotification(json.message);
                    this.showShiftModal = false;
                    await this.fetchEmployees();
                } else {
                    this.showNotification(json.message || 'Gagal mengatur shift', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Terjadi kesalahan sistem', 'error');
            } finally {
                this.loading = false;
            }
        },
        async deleteEmployee(emp) {
            if (!confirm(`Hapus karyawan ${emp.name}?`)) return;
            this.loading = true;
            try {
                const res = await fetch(`/api/v1/operasional/karyawan/${emp.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.showNotification(json.message);
                    await this.fetchEmployees();
                } else {
                    this.showNotification(json.message || 'Gagal menghapus karyawan', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Terjadi kesalahan sistem', 'error');
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
@endsection
