@extends('layouts.admin')

@section('title', 'UMKMGo - Inventory / Inventaris')

@section('content')
<!-- END: TopHeader -->
<!-- BEGIN: MainBody -->
<main class="p-8 flex-1 overflow-y-auto">
<!-- Title & Action Button -->
<div class="flex items-center justify-between mb-6">
<div>
<h1 class="text-2xl font-bold text-slate-900 tracking-tight">Inventory / Inventaris</h1>
<p class="text-sm text-slate-500 mt-1">Mengelola stok barang dan bahan baku</p>
</div>
<button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg shadow-sm transition-colors" type="button">
<span>+</span>
<span>Update Stok / Opname</span>
</button>
</div>
<!-- BEGIN: StatCards -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" data-purpose="inventory-summary-cards">
<!-- Total SKU -->
<div class="bg-white rounded-xl p-5 border border-slate-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
<svg class="w-6 h-6 stroke-[1.8]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<span class="text-xs font-medium text-slate-500">Total SKU</span>
<div class="flex items-baseline gap-1.5 mt-0.5">
<span class="text-2xl font-bold text-slate-900">30</span>
<span class="text-xs text-slate-500 font-normal">Produk</span>
</div>
</div>
</div>
<!-- Stok Aman -->
<div class="bg-white rounded-xl p-5 border border-slate-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
<svg class="w-6 h-6 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<circle cx="12" cy="12" r="9"></circle>
<path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<span class="text-xs font-medium text-slate-500">Stok Aman</span>
<div class="flex items-baseline gap-1.5 mt-0.5">
<span class="text-2xl font-bold text-slate-900">17</span>
<span class="text-xs text-slate-500 font-normal">SKU</span>
</div>
</div>
</div>
<!-- Stok Menipis -->
<div class="bg-white rounded-xl p-5 border border-slate-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0">
<svg class="w-6 h-6 stroke-[1.8]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<span class="text-xs font-medium text-slate-500">Stok Menipis</span>
<div class="flex items-baseline gap-1.5 mt-0.5">
<span class="text-2xl font-bold text-slate-900">8</span>
<span class="text-xs text-slate-500 font-normal">SKU</span>
</div>
</div>
</div>
<!-- Stok Kosong -->
<div class="bg-white rounded-xl p-5 border border-slate-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 flex-shrink-0">
<svg class="w-6 h-6 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<circle cx="12" cy="12" r="9"></circle>
<path d="M15 9l-6 6m0-6l6 6" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<span class="text-xs font-medium text-slate-500">Stok Kosong</span>
<div class="flex items-baseline gap-1.5 mt-0.5">
<span class="text-2xl font-bold text-slate-900">5</span>
<span class="text-xs text-slate-500 font-normal">SKU</span>
</div>
</div>
</div>
</section>
<!-- END: StatCards -->
<!-- BEGIN: FilterBar -->
<section class="flex flex-wrap items-center justify-between gap-3 mb-4" data-purpose="inventory-filters">
<!-- Left Filter Controls -->
<div class="flex flex-wrap items-center gap-3 flex-1">
<!-- Search Input -->
<div class="relative w-full max-w-sm">
<div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<circle cx="11" cy="11" r="8"></circle>
<line x1="21" x2="16.65" y1="21" y2="16.65"></line>
</svg>
</div>
<input class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Cari SKU, nama produk..." type="text"/>
</div>
<!-- Status Dropdown -->
<div class="relative">
<button class="inline-flex items-center justify-between gap-2 px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none" type="button">
<span>Semua Status Stok</span>
<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
<!-- Category Dropdown -->
<div class="relative">
<button class="inline-flex items-center justify-between gap-2 px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none" type="button">
<span>Kategori</span>
<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</div>
<!-- Right Export Button -->
<div>
<button class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 font-medium shadow-sm hover:bg-slate-50 transition-colors" type="button">
<svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
<span>Unduh Laporan</span>
</button>
</div>
</section>
<!-- END: FilterBar -->
<!-- BEGIN: InventoryTable -->
<section class="bg-white border border-slate-200/90 rounded-xl overflow-hidden shadow-[0_1px_3px_rgba(0,0,0,0.03)]" data-purpose="inventory-table-container">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse text-sm">
<thead>
<tr class="border-b border-slate-100 text-slate-400 font-medium text-xs bg-slate-50/50">
<th class="py-3.5 px-6" scope="col">
<div class="flex items-center gap-1.5 cursor-pointer select-none">
<span>Nama &amp; SKU</span>
<svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M7 16V4m0 0L3 8m4-4l4 4m6 4v12m0 0l4-4m-4 4l-4-4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
</th>
<th class="py-3.5 px-6" scope="col">Kategori</th>
<th class="py-3.5 px-6" scope="col">Stok</th>
<th class="py-3.5 px-6" scope="col">Satuan</th>
<th class="py-3.5 px-6" scope="col">Status</th>
<th class="py-3.5 px-6" scope="col">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100 text-slate-700">
<!-- Row 1: Coffe Americano -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-3 px-6">
<div class="flex items-center gap-3.5">
<img alt="Coffe Americano" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-slate-100 shadow-xs" src="{{ asset('images/stitch_assets/asset_27_7d744c4699.jpg') }}"/>
<div>
<div class="font-semibold text-slate-900 leading-snug">Coffe Americano</div>
<div class="text-xs text-slate-400 font-normal mt-0.5">COF-001</div>
</div>
</div>
</td>
<td class="py-3 px-6 text-slate-600">Makanan &amp; Minuman</td>
<td class="py-3 px-6 font-semibold text-slate-900">42</td>
<td class="py-3 px-6 text-slate-600">Porsi</td>
<td class="py-3 px-6">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aman
                  </span>
</td>
<td class="py-3 px-6">
<div class="flex items-center gap-4 text-xs font-medium text-blue-600">
<button class="flex items-center gap-1 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="flex items-center gap-1 text-slate-600 hover:text-slate-900 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      History
                    </button>
</div>
</td>
</tr>
<!-- Row 2: Tas Slempang -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-3 px-6">
<div class="flex items-center gap-3.5">
<img alt="Tas Slempang" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-slate-100 shadow-xs" src="{{ asset('images/stitch_assets/asset_2_0a10a14285.jpg') }}"/>
<div>
<div class="font-semibold text-slate-900 leading-snug">Tas Slempang</div>
<div class="text-xs text-slate-400 font-normal mt-0.5">FAS-001</div>
</div>
</div>
</td>
<td class="py-3 px-6 text-slate-600">Fashion</td>
<td class="py-3 px-6 font-semibold text-slate-900">12</td>
<td class="py-3 px-6 text-slate-600">Pcs</td>
<td class="py-3 px-6">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aman
                  </span>
</td>
<td class="py-3 px-6">
<div class="flex items-center gap-4 text-xs font-medium text-blue-600">
<button class="flex items-center gap-1 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="flex items-center gap-1 text-slate-600 hover:text-slate-900 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      History
                    </button>
</div>
</td>
</tr>
<!-- Row 3: Kentang Goreng -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-3 px-6">
<div class="flex items-center gap-3.5">
<img alt="Kentang Goreng" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-slate-100 shadow-xs" src="{{ asset('images/stitch_assets/asset_20_6ce6c6cc0a.jpg') }}"/>
<div>
<div class="font-semibold text-slate-900 leading-snug">Kentang Goreng</div>
<div class="text-xs text-slate-400 font-normal mt-0.5">SNK-001</div>
</div>
</div>
</td>
<td class="py-3 px-6 text-slate-600">Makanan &amp; Minuman</td>
<td class="py-3 px-6 font-semibold text-amber-500">5</td>
<td class="py-3 px-6 text-slate-600">Porsi</td>
<td class="py-3 px-6">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600">
<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Menipis
                  </span>
</td>
<td class="py-3 px-6">
<div class="flex items-center gap-4 text-xs font-medium text-blue-600">
<button class="flex items-center gap-1 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="flex items-center gap-1 text-slate-600 hover:text-slate-900 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      History
                    </button>
</div>
</td>
</tr>
<!-- Row 4: Baju MLBB Nana -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-3 px-6">
<div class="flex items-center gap-3.5">
<div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center p-1.5 flex-shrink-0 border border-slate-200">
<svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewbox="0 0 24 24">
<path d="M4 6l4-2 4 2 4-2 4 2v5l-3 1v10H7V12l-3-1V6z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<div class="font-semibold text-slate-900 leading-snug">Baju MLBB Nana</div>
<div class="text-xs text-slate-400 font-normal mt-0.5">FAS-002</div>
</div>
</div>
</td>
<td class="py-3 px-6 text-slate-600">Fashion</td>
<td class="py-3 px-6 font-semibold text-amber-500">8</td>
<td class="py-3 px-6 text-slate-600">Pcs</td>
<td class="py-3 px-6">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600">
<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Menipis
                  </span>
</td>
<td class="py-3 px-6">
<div class="flex items-center gap-4 text-xs font-medium text-blue-600">
<button class="flex items-center gap-1 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="flex items-center gap-1 text-slate-600 hover:text-slate-900 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      History
                    </button>
</div>
</td>
</tr>
<!-- Row 5: Pizza Triple -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-3 px-6">
<div class="flex items-center gap-3.5">
<img alt="Pizza Triple" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-slate-100 shadow-xs" src="{{ asset('images/stitch_assets/asset_1_28e5243a08.jpg') }}"/>
<div>
<div class="font-semibold text-slate-900 leading-snug">Pizza Triple</div>
<div class="text-xs text-slate-400 font-normal mt-0.5">FNB-002</div>
</div>
</div>
</td>
<td class="py-3 px-6 text-slate-600">Makanan &amp; Minuman</td>
<td class="py-3 px-6 font-semibold text-slate-900">15</td>
<td class="py-3 px-6 text-slate-600">Porsi</td>
<td class="py-3 px-6">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aman
                  </span>
</td>
<td class="py-3 px-6">
<div class="flex items-center gap-4 text-xs font-medium text-blue-600">
<button class="flex items-center gap-1 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="flex items-center gap-1 text-slate-600 hover:text-slate-900 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      History
                    </button>
</div>
</td>
</tr>
<!-- Row 6: Celana Skena -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-3 px-6">
<div class="flex items-center gap-3.5">
<div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center p-2 flex-shrink-0 border border-slate-200">
<svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" stroke-width="1.6" viewbox="0 0 24 24">
<path d="M6 3h12v4l-1 14h-3.5L12 11l-1.5 10H7L6 7V3z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<div class="font-semibold text-slate-900 leading-snug">Celana Skena</div>
<div class="text-xs text-slate-400 font-normal mt-0.5">FAS-003</div>
</div>
</div>
</td>
<td class="py-3 px-6 text-slate-600">Fashion</td>
<td class="py-3 px-6 font-semibold text-rose-500">0</td>
<td class="py-3 px-6 text-slate-600">Pcs</td>
<td class="py-3 px-6">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-500">
<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Kosong
                  </span>
</td>
<td class="py-3 px-6">
<div class="flex items-center gap-4 text-xs font-medium text-blue-600">
<button class="flex items-center gap-1 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="flex items-center gap-1 text-slate-600 hover:text-slate-900 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      History
                    </button>
</div>
</td>
</tr>
<!-- Row 7: Nasi Gila -->
<tr class="hover:bg-slate-50/60 transition-colors">
<td class="py-3 px-6">
<div class="flex items-center gap-3.5">
<img alt="Nasi Gila" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-slate-100 shadow-xs" src="{{ asset('images/stitch_assets/asset_4_8b94ab8dce.jpg') }}"/>
<div>
<div class="font-semibold text-slate-900 leading-snug">Nasi Gila</div>
<div class="text-xs text-slate-400 font-normal mt-0.5">FNB-003</div>
</div>
</div>
</td>
<td class="py-3 px-6 text-slate-600">Makanan &amp; Minuman</td>
<td class="py-3 px-6 font-semibold text-amber-500">3</td>
<td class="py-3 px-6 text-slate-600">Porsi</td>
<td class="py-3 px-6">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600">
<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Menipis
                  </span>
</td>
<td class="py-3 px-6">
<div class="flex items-center gap-4 text-xs font-medium text-blue-600">
<button class="flex items-center gap-1 hover:text-blue-800 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      Edit
                    </button>
<button class="flex items-center gap-1 text-slate-600 hover:text-slate-900 transition-colors">
<svg class="w-3.5 h-3.5 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                      History
                    </button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</section>
<!-- END: InventoryTable -->
</main>
@endsection
