@extends('layouts.admin')

@section('title', 'UMKMGo - Kelola Produk')

@section('content')
<!-- END: TopBar -->
<!-- BEGIN: MainContentArea -->
<main class="p-8 flex-1 flex flex-col justify-between" data-purpose="product-list-page">
<div>
<!-- Page Title & Add Button Header -->
<div class="flex items-center justify-between mb-8">
<div>
<h1 class="text-2xl font-bold text-slate-900 tracking-tight">Produk</h1>
<p class="text-sm text-slate-500 mt-1 font-normal">Total 30 Produk Aktif</p>
</div>
<button class="bg-[#2563EB] hover:bg-[#1d4ed8] text-white px-5 py-2.5 rounded-xl font-medium text-sm flex items-center gap-2 shadow-sm transition-colors" type="button">
<span>+</span>
<span>Tambah Produk</span>
</button>
</div>
<!-- Filter and Search Row -->
<div class="grid grid-cols-12 gap-4 mb-6">
<!-- Search Bar -->
<div class="col-span-12 lg:col-span-8 relative">
<span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</span>
<input class="w-full bg-white border border-slate-200/90 rounded-xl pl-11 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm" placeholder="Cari Nama Produk" type="text"/>
</div>
<!-- Kategori Filter -->
<div class="col-span-6 lg:col-span-2 relative">
<select class="w-full bg-white border border-slate-200/90 rounded-xl px-4 py-2.5 text-sm text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer shadow-sm">
<option>Kategori</option>
<option>Makanan &amp; Minuman</option>
<option>Fashion</option>
</select>
<div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
</div>
<!-- Sort Filter -->
<div class="col-span-6 lg:col-span-2 relative">
<select class="w-full bg-white border border-slate-200/90 rounded-xl px-4 py-2.5 text-sm text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer shadow-sm">
<option>Urutan: Terbaru</option>
<option>Urutan: Terlama</option>
<option>Urutan: Harga Tertinggi</option>
<option>Urutan: Harga Terendah</option>
</select>
<div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
</div>
</div>
<!-- Table Container -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse" data-purpose="product-table">
<thead>
<tr class="border-b border-slate-200/80 text-[12.5px] font-semibold text-slate-600 bg-white">
<th class="py-4 px-6 font-semibold" scope="col">Nama Produk</th>
<th class="py-4 px-6 font-semibold" scope="col">Kategori</th>
<th class="py-4 px-6 font-semibold" scope="col">Harga Jual</th>
<th class="py-4 px-6 font-semibold" scope="col">Harga Beli</th>
<th class="py-4 px-6 font-semibold" scope="col">Stok</th>
<th class="py-4 px-6 font-semibold" scope="col">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100 text-sm">
<!-- Row 1: Coffe Americano -->
<tr class="hover:bg-slate-50/70 transition-colors">
<td class="py-3.5 px-6">
<div class="flex items-center gap-3.5">
<img alt="Coffe Americano" class="w-11 h-11 rounded-xl object-cover border border-slate-200/60 shadow-sm flex-shrink-0" src="{{ asset('images/stitch_assets/asset_15_8ba5c5ea5d.jpg') }}"/>
<div>
<div class="font-medium text-slate-900 text-sm">Coffe Americano</div>
<div class="text-xs text-slate-400 mt-0.5">PRD-001</div>
</div>
</div>
</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-[#EEF2F6] text-slate-600">
                      Makanan &amp; Minuman
                    </span>
</td>
<td class="py-3.5 px-6 font-medium text-slate-800">Rp 15.000</td>
<td class="py-3.5 px-6 text-slate-600">Rp 7.000</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                      42 porsi
                    </span>
</td>
<td class="py-3.5 px-6">
<div class="flex items-center gap-3">
<button class="text-slate-400 hover:text-slate-600 p-1 rounded transition-colors" title="Edit" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
<button class="text-red-400 hover:text-red-600 p-1 rounded transition-colors" title="Hapus" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</td>
</tr>
<!-- Row 2: Tas Slempang -->
<tr class="hover:bg-slate-50/70 transition-colors">
<td class="py-3.5 px-6">
<div class="flex items-center gap-3.5">
<img alt="Tas Slempang" class="w-11 h-11 rounded-xl object-cover border border-slate-200/60 shadow-sm flex-shrink-0" src="{{ asset('images/stitch_assets/asset_17_d702f5871a.jpg') }}"/>
<div>
<div class="font-medium text-slate-900 text-sm">Tas Slempang</div>
<div class="text-xs text-slate-400 mt-0.5">PRD-002</div>
</div>
</div>
</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-[#EEF2F6] text-slate-600">
                      Fashion
                    </span>
</td>
<td class="py-3.5 px-6 font-medium text-slate-800">Rp 65.000</td>
<td class="py-3.5 px-6 text-slate-600">Rp 40.000</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                      12 pcs
                    </span>
</td>
<td class="py-3.5 px-6">
<div class="flex items-center gap-3">
<button class="text-slate-400 hover:text-slate-600 p-1 rounded transition-colors" title="Edit" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
<button class="text-red-400 hover:text-red-600 p-1 rounded transition-colors" title="Hapus" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</td>
</tr>
<!-- Row 3: Kentang Goreng -->
<tr class="hover:bg-slate-50/70 transition-colors">
<td class="py-3.5 px-6">
<div class="flex items-center gap-3.5">
<img alt="Kentang Goreng" class="w-11 h-11 rounded-xl object-cover border border-slate-200/60 shadow-sm flex-shrink-0" src="{{ asset('images/stitch_assets/asset_3_113cc1a375.jpg') }}"/>
<div>
<div class="font-medium text-slate-900 text-sm">Kentang Goreng</div>
<div class="text-xs text-slate-400 mt-0.5">PRD-003</div>
</div>
</div>
</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-[#EEF2F6] text-slate-600">
                      Makanan &amp; Minuman
                    </span>
</td>
<td class="py-3.5 px-6 font-medium text-slate-800">Rp 20.000</td>
<td class="py-3.5 px-6 text-slate-600">Rp 10.000</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200/60">
<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                      5 porsi
                    </span>
</td>
<td class="py-3.5 px-6">
<div class="flex items-center gap-3">
<button class="text-slate-400 hover:text-slate-600 p-1 rounded transition-colors" title="Edit" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
<button class="text-red-400 hover:text-red-600 p-1 rounded transition-colors" title="Hapus" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</td>
</tr>
<!-- Row 4: Baju MLBB Nana -->
<tr class="hover:bg-slate-50/70 transition-colors">
<td class="py-3.5 px-6">
<div class="flex items-center gap-3.5">
<img alt="Baju MLBB Nana" class="w-11 h-11 rounded-xl object-cover border border-slate-200/60 shadow-sm flex-shrink-0" src="{{ asset('images/stitch_assets/asset_10_582a66dbf1.jpg') }}"/>
<div>
<div class="font-medium text-slate-900 text-sm">Baju MLBB Nana</div>
<div class="text-xs text-slate-400 mt-0.5">PRD-004</div>
</div>
</div>
</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-[#EEF2F6] text-slate-600">
                      Fashion
                    </span>
</td>
<td class="py-3.5 px-6 font-medium text-slate-800">Rp 90.000</td>
<td class="py-3.5 px-6 text-slate-600">Rp 60.000</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200/60">
<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                      8 pcs
                    </span>
</td>
<td class="py-3.5 px-6">
<div class="flex items-center gap-3">
<button class="text-slate-400 hover:text-slate-600 p-1 rounded transition-colors" title="Edit" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
<button class="text-red-400 hover:text-red-600 p-1 rounded transition-colors" title="Hapus" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</td>
</tr>
<!-- Row 5: Pizza Triple -->
<tr class="hover:bg-slate-50/70 transition-colors">
<td class="py-3.5 px-6">
<div class="flex items-center gap-3.5">
<img alt="Pizza Triple" class="w-11 h-11 rounded-xl object-cover border border-slate-200/60 shadow-sm flex-shrink-0" src="{{ asset('images/stitch_assets/asset_24_b9fd08003b.jpg') }}"/>
<div>
<div class="font-medium text-slate-900 text-sm">Pizza Triple</div>
<div class="text-xs text-slate-400 mt-0.5">PRD-005</div>
</div>
</div>
</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-[#EEF2F6] text-slate-600">
                      Makanan &amp; Minuman
                    </span>
</td>
<td class="py-3.5 px-6 font-medium text-slate-800">Rp 50.000</td>
<td class="py-3.5 px-6 text-slate-600">Rp 25.000</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                      15 porsi
                    </span>
</td>
<td class="py-3.5 px-6">
<div class="flex items-center gap-3">
<button class="text-slate-400 hover:text-slate-600 p-1 rounded transition-colors" title="Edit" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
<button class="text-red-400 hover:text-red-600 p-1 rounded transition-colors" title="Hapus" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</td>
</tr>
<!-- Row 6: Celana Skena -->
<tr class="hover:bg-slate-50/70 transition-colors">
<td class="py-3.5 px-6">
<div class="flex items-center gap-3.5">
<img alt="Celana Skena" class="w-11 h-11 rounded-xl object-cover border border-slate-200/60 shadow-sm flex-shrink-0" src="{{ asset('images/stitch_assets/asset_19_c495be691f.jpg') }}"/>
<div>
<div class="font-medium text-slate-900 text-sm">Celana Skena</div>
<div class="text-xs text-slate-400 mt-0.5">PRD-006</div>
</div>
</div>
</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-[#EEF2F6] text-slate-600">
                      Fashion
                    </span>
</td>
<td class="py-3.5 px-6 font-medium text-slate-800">Rp 100.000</td>
<td class="py-3.5 px-6 text-slate-600">Rp 60.000</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full border border-rose-200/60">
<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                      0 pcs
                    </span>
</td>
<td class="py-3.5 px-6">
<div class="flex items-center gap-3">
<button class="text-slate-400 hover:text-slate-600 p-1 rounded transition-colors" title="Edit" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
<button class="text-red-400 hover:text-red-600 p-1 rounded transition-colors" title="Hapus" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</td>
</tr>
<!-- Row 7: Nasi Gila -->
<tr class="hover:bg-slate-50/70 transition-colors">
<td class="py-3.5 px-6">
<div class="flex items-center gap-3.5">
<img alt="Nasi Gila" class="w-11 h-11 rounded-xl object-cover border border-slate-200/60 shadow-sm flex-shrink-0" src="{{ asset('images/stitch_assets/asset_25_823e74fd48.jpg') }}"/>
<div>
<div class="font-medium text-slate-900 text-sm">Nasi Gila</div>
<div class="text-xs text-slate-400 mt-0.5">PRD-007</div>
</div>
</div>
</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-[#EEF2F6] text-slate-600">
                      Makanan &amp; Minuman
                    </span>
</td>
<td class="py-3.5 px-6 font-medium text-slate-800">Rp 35.000</td>
<td class="py-3.5 px-6 text-slate-600">Rp 12.500</td>
<td class="py-3.5 px-6">
<span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200/60">
<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                      3 porsi
                    </span>
</td>
<td class="py-3.5 px-6">
<div class="flex items-center gap-3">
<button class="text-slate-400 hover:text-slate-600 p-1 rounded transition-colors" title="Edit" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
<button class="text-red-400 hover:text-red-600 p-1 rounded transition-colors" title="Hapus" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<!-- BEGIN: PaginationFooter -->
<div class="flex items-center justify-between pt-6 text-sm text-slate-500" data-purpose="pagination-container">
<span>Menampilkan 1-7 dari 30 produk</span>
<div class="flex items-center gap-1.5">
<!-- Prev Button -->
<button class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-700 transition-colors" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
<!-- Page 1 (Active) -->
<button class="w-8 h-8 rounded-lg bg-[#2563EB] text-white font-medium flex items-center justify-center shadow-sm" type="button">
            1
          </button>
<!-- Page 2 -->
<button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-600 font-normal flex items-center justify-center transition-colors" type="button">
            2
          </button>
<!-- Page 3 -->
<button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-600 font-normal flex items-center justify-center transition-colors" type="button">
            3
          </button>
<!-- Page 4 -->
<button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-600 font-normal flex items-center justify-center transition-colors" type="button">
            4
          </button>
<!-- Page 5 -->
<button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-600 font-normal flex items-center justify-center transition-colors" type="button">
            5
          </button>
<!-- Next Button -->
<button class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-700 transition-colors" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</div>
<!-- END: PaginationFooter -->
</main>
@endsection
