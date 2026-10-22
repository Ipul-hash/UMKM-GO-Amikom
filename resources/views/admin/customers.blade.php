@extends('layouts.admin')

@section('title', 'UMKMGo - Pelanggan')

@section('content')
<!-- END: TopBar -->
<!-- BEGIN: PageContent -->
<main class="p-8 space-y-6">
<!-- Header Section -->
<section class="flex items-center justify-between" data-purpose="header-section">
<div>
<h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pelanggan</h1>
<p class="text-sm text-slate-500 mt-1">Kelola data pelanggan dan program loyalitas</p>
</div>
<!-- Add Customer Button -->
<button class="bg-[#2563eb] hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-sm shadow-blue-500/20 flex items-center gap-2 transition-all">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
<span class="text-sm">Tambah Pelanggan</span>
</button>
</section>
<!-- BEGIN: MetricCards -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6" data-purpose="metrics">
<!-- Card 1: Total Pelanggan -->
<div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_2px_4px_rgba(0,0,0,0.02)] flex items-center justify-between">
<div>
<div class="text-[13px] font-medium text-slate-500">Total Pelanggan</div>
<div class="flex items-baseline gap-2 mt-2">
<span class="text-3xl font-bold text-slate-900">1.240</span>
<span class="text-sm font-medium text-slate-500">Orang</span>
</div>
</div>
<div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
</div>
<!-- Card 2: Pelanggan Baru Bulan Ini -->
<div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_2px_4px_rgba(0,0,0,0.02)] flex items-center justify-between">
<div>
<div class="text-[13px] font-medium text-slate-500">Pelanggan Baru Bulan Ini</div>
<div class="flex items-baseline gap-2 mt-2">
<span class="text-3xl font-bold text-slate-900">48</span>
<span class="text-sm font-medium text-slate-500">Orang</span>
</div>
</div>
<div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
</div>
<!-- Card 3: Member VIP -->
<div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_2px_4px_rgba(0,0,0,0.02)] flex items-center justify-between">
<div>
<div class="text-[13px] font-medium text-slate-500">Member VIP</div>
<div class="flex items-baseline gap-2 mt-2">
<span class="text-3xl font-bold text-slate-900">156</span>
<span class="text-sm font-medium text-slate-500">Orang</span>
</div>
</div>
<div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
<!-- Crown VIP Icon -->
<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M3 18h18v2H3v-2zm2-2l2.5-9 4.5 4 4.5-4L19 16H5z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
</div>
</section>
<!-- END: MetricCards -->
<!-- BEGIN: FilterBar -->
<section class="flex items-center justify-between gap-4" data-purpose="filter-bar">
<div class="flex items-center gap-4 flex-1 max-w-2xl">
<!-- Search Input -->
<div class="relative flex-1">
<span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<circle cx="11" cy="11" r="8"></circle>
<line x1="21" x2="16.65" y1="21" y2="16.65"></line>
</svg>
</span>
<input class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Cari nama, No. WA..." type="text"/>
</div>
<!-- Status Member Dropdown -->
<div class="relative min-w-[180px]">
<select class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer pr-10">
<option>Status Member</option>
<option>Gold</option>
<option>Platinum</option>
<option>Regular</option>
</select>
<span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<polyline points="6 9 12 15 18 9"></polyline>
</svg>
</span>
</div>
</div>
<!-- Export Data Button -->
<button class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium px-4 py-2.5 rounded-xl text-sm flex items-center gap-2 transition-colors">
<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
<span>Export Data</span>
</button>
</section>
<!-- END: FilterBar -->
<!-- BEGIN: CustomerTableCard -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_4px_rgba(0,0,0,0.02)] overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<!-- Table Header -->
<thead>
<tr class="border-b border-slate-100 text-[12px] font-semibold text-slate-500 bg-white">
<th class="py-4 px-6 font-semibold">Nama &amp; No. WA</th>
<th class="py-4 px-6 font-semibold">Total Transaksi</th>
<th class="py-4 px-6 font-semibold">Total Belanja</th>
<th class="py-4 px-6 font-semibold">Poin</th>
<th class="py-4 px-6 font-semibold">Status Member</th>
<th class="py-4 px-6 font-semibold">Aksi</th>
</tr>
</thead>
<!-- Table Body -->
<tbody class="divide-y divide-slate-100 text-sm">
<!-- Row 1: Andi Saputra -->
<tr class="hover:bg-slate-50/50 transition-colors">
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-[#dbeafe] text-[#2563eb] font-semibold text-xs flex items-center justify-center flex-shrink-0">
                      AS
                    </div>
<div>
<div class="font-bold text-slate-800 text-[14px]">Andi Saputra</div>
<div class="text-xs text-slate-400 mt-0.5">0812-3456-7890</div>
</div>
</div>
</td>
<td class="py-4 px-6 text-slate-600 font-medium">18x</td>
<td class="py-4 px-6 text-slate-600 font-medium">Rp 1.450.000</td>
<td class="py-4 px-6 text-slate-600 font-medium">240 Pts</td>
<td class="py-4 px-6">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#fef3c7] text-[#b45309]">
<span class="w-1.5 h-1.5 rounded-full bg-[#b45309]"></span>
                    Gold
                  </span>
</td>
<td class="py-4 px-6">
<div class="flex items-center gap-4 text-xs font-medium">
<button class="flex items-center gap-1 text-slate-500 hover:text-slate-700">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="text-blue-600 hover:text-blue-700 font-semibold">
                      Detail
                    </button>
</div>
</td>
</tr>
<!-- Row 2: Budi Hartono -->
<tr class="hover:bg-slate-50/50 transition-colors">
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-[#f3e8ff] text-[#9333ea] font-semibold text-xs flex items-center justify-center flex-shrink-0">
                      BH
                    </div>
<div>
<div class="font-bold text-slate-800 text-[14px]">Budi Hartono</div>
<div class="text-xs text-slate-400 mt-0.5">0813-9876-5432</div>
</div>
</div>
</td>
<td class="py-4 px-6 text-slate-600 font-medium">12x</td>
<td class="py-4 px-6 text-slate-600 font-medium">Rp 820.000</td>
<td class="py-4 px-6 text-slate-600 font-medium">110 Pts</td>
<td class="py-4 px-6">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-[#f1f5f9] text-[#64748b]">
<span class="w-1.5 h-1.5 rounded-full bg-[#94a3b8]"></span>
                    Regular
                  </span>
</td>
<td class="py-4 px-6">
<div class="flex items-center gap-4 text-xs font-medium">
<button class="flex items-center gap-1 text-slate-500 hover:text-slate-700">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="text-blue-600 hover:text-blue-700 font-semibold">
                      Detail
                    </button>
</div>
</td>
</tr>
<!-- Row 3: Citra Kirana -->
<tr class="hover:bg-slate-50/50 transition-colors">
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-[#fce7f3] text-[#db2777] font-semibold text-xs flex items-center justify-center flex-shrink-0">
                      CK
                    </div>
<div>
<div class="font-bold text-slate-800 text-[14px]">Citra Kirana</div>
<div class="text-xs text-slate-400 mt-0.5">0857-1122-3344</div>
</div>
</div>
</td>
<td class="py-4 px-6 text-slate-600 font-medium">25x</td>
<td class="py-4 px-6 text-slate-600 font-medium">Rp 2.300.000</td>
<td class="py-4 px-6 text-slate-600 font-medium">510 Pts</td>
<td class="py-4 px-6">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#dbeafe] text-[#1d4ed8]">
<span class="w-1.5 h-1.5 rounded-full bg-[#1d4ed8]"></span>
                    Platinum
                  </span>
</td>
<td class="py-4 px-6">
<div class="flex items-center gap-4 text-xs font-medium">
<button class="flex items-center gap-1 text-slate-500 hover:text-slate-700">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="text-blue-600 hover:text-blue-700 font-semibold">
                      Detail
                    </button>
</div>
</td>
</tr>
<!-- Row 4: Dini Lestari -->
<tr class="hover:bg-slate-50/50 transition-colors">
<td class="py-4 px-6">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-[#ccfbf1] text-[#0d9488] font-semibold text-xs flex items-center justify-center flex-shrink-0">
                      DL
                    </div>
<div>
<div class="font-bold text-slate-800 text-[14px]">Dini Lestari</div>
<div class="text-xs text-slate-400 mt-0.5">0821-5544-3322</div>
</div>
</div>
</td>
<td class="py-4 px-6 text-slate-600 font-medium">5x</td>
<td class="py-4 px-6 text-slate-600 font-medium">Rp 340.000</td>
<td class="py-4 px-6 text-slate-600 font-medium">45 Pts</td>
<td class="py-4 px-6">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-[#f1f5f9] text-[#64748b]">
<span class="w-1.5 h-1.5 rounded-full bg-[#94a3b8]"></span>
                    Regular
                  </span>
</td>
<td class="py-4 px-6">
<div class="flex items-center gap-4 text-xs font-medium">
<button class="flex items-center gap-1 text-slate-500 hover:text-slate-700">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="text-blue-600 hover:text-blue-700 font-semibold">
                      Detail
                    </button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- BEGIN: Pagination -->
<div class="px-6 py-4 flex items-center justify-between border-t border-slate-100 bg-white">
<div class="text-xs text-slate-500">
            Menampilkan 1-4 dari 1.240 pelanggan
          </div>
<!-- Page Buttons -->
<div class="flex items-center gap-1">
<!-- Prev -->
<button class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<polyline points="15 18 9 12 15 6"></polyline>
</svg>
</button>
<!-- Page 1 (Active) -->
<button class="w-7 h-7 rounded-lg bg-[#2563eb] text-white text-xs font-semibold flex items-center justify-center shadow-sm">
              1
            </button>
<!-- Page 2 -->
<button class="w-7 h-7 rounded-lg text-slate-600 hover:bg-slate-100 text-xs font-medium flex items-center justify-center transition-colors">
              2
            </button>
<!-- Page 3 -->
<button class="w-7 h-7 rounded-lg text-slate-600 hover:bg-slate-100 text-xs font-medium flex items-center justify-center transition-colors">
              3
            </button>
<!-- Page 4 -->
<button class="w-7 h-7 rounded-lg text-slate-600 hover:bg-slate-100 text-xs font-medium flex items-center justify-center transition-colors">
              4
            </button>
<!-- Page 5 -->
<button class="w-7 h-7 rounded-lg text-slate-600 hover:bg-slate-100 text-xs font-medium flex items-center justify-center transition-colors">
              5
            </button>
<!-- Next -->
<button class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<polyline points="9 18 15 12 9 6"></polyline>
</svg>
</button>
</div>
</div>
<!-- END: Pagination -->
</div>
<!-- END: CustomerTableCard -->
</main>
<!-- END: PageContent -->
@endsection
