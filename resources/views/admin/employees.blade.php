@extends('layouts.admin')

@section('title', 'UMKMGo - Manajemen Karyawan')

@section('content')
<!-- END: TopNavbar -->
<!-- BEGIN: MainContent -->
<main class="flex-1 p-8 overflow-y-auto">
<!-- Page Title & Primary Action -->
<div class="flex items-center justify-between mb-7">
<div>
<h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Karyawan</h1>
<p class="text-[13px] text-slate-500 mt-1">Kelola staf, hak akses, dan jadwal shift</p>
</div>
<button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-[13px] px-4 py-2.5 rounded-xl shadow-sm transition-all duration-150">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
          Tambah Karyawan
        </button>
</div>
<!-- KPI Summary Cards Grid -->
<div class="grid grid-cols-2 gap-6 mb-7">
<!-- Card 1: Total Karyawan -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 flex justify-between items-start shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
<div class="flex flex-col">
<span class="text-[13px] font-medium text-slate-500">Total Karyawan</span>
<div class="mt-4 flex items-baseline gap-2">
<span class="text-3xl font-bold text-slate-900 tracking-tight">6</span>
<span class="text-sm font-medium text-slate-500">Orang</span>
</div>
</div>
<div class="w-11 h-11 rounded-xl bg-blue-50/80 text-blue-600 flex items-center justify-center">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
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
<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
<span class="text-[13px] text-slate-600">Pagi:</span>
<span class="text-xl font-bold text-slate-900 ml-0.5">3</span>
</div>
<div class="w-px h-5 bg-slate-200"></div>
<!-- Shift Malam -->
<div class="flex items-center gap-2">
<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
<span class="text-[13px] text-slate-600">Malam:</span>
<span class="text-xl font-bold text-slate-900 ml-0.5">3</span>
</div>
</div>
</div>
<div class="w-11 h-11 rounded-xl bg-blue-50/80 text-blue-600 flex items-center justify-center">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
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
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</span>
<input class="w-full pl-9 pr-3.5 py-2 text-[13px] rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400" placeholder="Cari nama karyawan..." type="text"/>
</div>
<!-- Filter Status Dropdown -->
<button class="inline-flex items-center gap-2 px-3.5 py-2 text-[13px] font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
<span>Semua Status</span>
<svg class="w-3.5 h-3.5 text-slate-400 ml-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
</div>
<!-- Table Content -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-t border-b border-slate-100 bg-[#fafafa]/50 text-[11px] font-medium text-slate-500 uppercase tracking-wider">
<th class="py-3 px-6">Nama Karyawan</th>
<th class="py-3 px-6">Peran / Jabatan</th>
<th class="py-3 px-6">Shift Kerja</th>
<th class="py-3 px-6">No. WhatsApp</th>
<th class="py-3 px-6">Status</th>
<th class="py-3 px-6 text-left">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100 text-[13px]">
<!-- Row 1: Budi Pratama -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-4 px-6 whitespace-nowrap">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-semibold text-xs flex items-center justify-center flex-shrink-0">
                      BP
                    </div>
<span class="font-bold text-slate-900">Budi Pratama</span>
</div>
</td>
<td class="py-4 px-6 whitespace-nowrap text-slate-600 font-normal">
                  Kasir Utama
                </td>
<td class="py-4 px-6 whitespace-nowrap">
<div class="flex flex-col">
<span class="text-slate-800 font-medium">Shift Pagi</span>
<span class="text-xs text-slate-400 mt-0.5">(08:00 - 16:00)</span>
</div>
</td>
<td class="py-4 px-6 whitespace-nowrap text-slate-600">
                  0812-1111-2222
                </td>
<td class="py-4 px-6 whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aktif
                  </span>
</td>
<td class="py-4 px-6 whitespace-nowrap text-xs font-semibold">
<div class="flex items-center gap-4">
<button class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
                      Edit
                    </button>
<button class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
                      Hapus
                    </button>
</div>
</td>
</tr>
<!-- Row 2: Siti Aminah -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-4 px-6 whitespace-nowrap">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 font-semibold text-xs flex items-center justify-center flex-shrink-0">
                      SA
                    </div>
<span class="font-bold text-slate-900">Siti Aminah</span>
</div>
</td>
<td class="py-4 px-6 whitespace-nowrap text-slate-600 font-normal">
                  Barista
                </td>
<td class="py-4 px-6 whitespace-nowrap">
<div class="flex flex-col">
<span class="text-slate-800 font-medium">Shift Pagi</span>
<span class="text-xs text-slate-400 mt-0.5">(08:00 - 16:00)</span>
</div>
</td>
<td class="py-4 px-6 whitespace-nowrap text-slate-600">
                  0813-3333-4444
                </td>
<td class="py-4 px-6 whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aktif
                  </span>
</td>
<td class="py-4 px-6 whitespace-nowrap text-xs font-semibold">
<div class="flex items-center gap-4">
<button class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
                      Edit
                    </button>
<button class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
                      Hapus
                    </button>
</div>
</td>
</tr>
<!-- Row 3: Roni Wijaya -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-4 px-6 whitespace-nowrap">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-xs flex items-center justify-center flex-shrink-0">
                      RW
                    </div>
<span class="font-bold text-slate-900">Roni Wijaya</span>
</div>
</td>
<td class="py-4 px-6 whitespace-nowrap text-slate-600 font-normal">
                  Staff Gudang
                </td>
<td class="py-4 px-6 whitespace-nowrap">
<div class="flex flex-col">
<span class="text-slate-800 font-medium">Shift Malam</span>
<span class="text-xs text-slate-400 mt-0.5">(16:00 - 00:00)</span>
</div>
</td>
<td class="py-4 px-6 whitespace-nowrap text-slate-600">
                  0857-5555-6666
                </td>
<td class="py-4 px-6 whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600">
<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Cuti
                  </span>
</td>
<td class="py-4 px-6 whitespace-nowrap text-xs font-semibold">
<div class="flex items-center gap-4">
<button class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
                      Edit
                    </button>
<button class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
                      Hapus
                    </button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Table Pagination Footer -->
<div class="px-6 py-4 flex items-center justify-between border-t border-slate-100">
<p class="text-[13px] text-slate-500">
            Menampilkan 3 karyawan
          </p>
<div class="flex items-center gap-1.5">
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 disabled:opacity-50 transition-colors" disabled="">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 font-semibold text-xs border border-blue-200">
              1
            </button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
</div>
</div>
<!-- END: Table Container -->
</main>
<!-- END: MainContent -->
</div>
<!-- END: MainContentWrapper -->
</body></html>
@endsection
