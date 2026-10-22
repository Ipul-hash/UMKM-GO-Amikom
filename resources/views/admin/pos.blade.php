@extends('layouts.admin')

@section('title', 'UMKMGo - POS / Kasir')

@section('content')
<!-- Main Content with 2 Columns: Catalog (Left) + Cart Panel (Right) -->
<div class="flex-1 flex overflow-hidden">
<!-- BEGIN: CatalogArea -->
<main class="flex-1 flex flex-col overflow-y-auto px-8 py-6 bg-white border-r border-slate-100">
<!-- Header POS Title & Active Status -->
<div class="flex items-start justify-between mb-5">
<div>
<h1 class="text-2xl font-bold text-slate-900 tracking-tight">POS / Kasir</h1>
<p class="text-xs text-slate-500 mt-1">Pilih produk dan mulai transaksi pelanggan Anda.</p>
</div>
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            Kasir aktif
          </span>
</div>
<!-- Search Bar and Filter Controls -->
<div class="flex items-center gap-3 mb-4">
<div class="relative flex-1">
<div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
<input class="w-full pl-10 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all" placeholder="Cari Nama Produk" type="text"/>
<div class="absolute inset-y-0 right-0 pr-3 flex items-center">
<kbd class="px-1.5 py-0.5 text-[10px] font-medium text-slate-400 bg-white border border-slate-200 rounded shadow-sm">/</kbd>
</div>
</div>
<button class="p-2 border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-600 transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<!-- Category Pills -->
<div class="flex items-center gap-2 overflow-x-auto pb-1 mb-4 no-scrollbar">
<button class="px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-sm">Semua</button>
<button class="px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200/70 transition-colors">Makanan &amp; Minuman</button>
<button class="px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200/70 transition-colors">Fashion</button>
<button class="px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200/70 transition-colors">Kecantikan</button>
<button class="px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200/70 transition-colors">Lainnya</button>
</div>
<!-- Section Products Header -->
<div class="flex items-center justify-between mb-4">
<h2 class="text-xs font-bold text-slate-900">Semua produk</h2>
<span class="text-[11px] text-slate-400">12 produk tersedia</span>
</div>
<!-- Product Cards Grid (3 Columns) -->
<div class="grid grid-cols-3 gap-4 pb-8">
<!-- Product Card 1: Coffe Americano -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Coffe Americano" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_23_9f68fb6a6f.jpg') }}"/>
<span class="absolute top-2 right-2 px-2 py-0.5 bg-blue-600 text-white text-[10px] font-medium rounded-full shadow">2 ditambahkan</span>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Coffe Americano</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 15.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 48</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 2: Matcha Latte -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Matcha Latte" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_22_0a6ec6d6f3.jpg') }}"/>
<span class="absolute top-2 right-2 px-2 py-0.5 bg-blue-600 text-white text-[10px] font-medium rounded-full shadow">1 ditambahkan</span>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Matcha Latte</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 18.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 35</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 3: Fries -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Fries" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_5_34ce585d08.jpg') }}"/>
<span class="absolute top-2 right-2 px-2 py-0.5 bg-blue-600 text-white text-[10px] font-medium rounded-full shadow">1 ditambahkan</span>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Fries</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 22.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 26</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 4: Tas Slempang -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Tas Slempang" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_18_a40178c038.jpg') }}"/>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Tas Slempang</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 65.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 12</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 5: Baju MLBB Nana -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Baju MLBB Nana" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_29_6e4e0b1181.jpg') }}"/>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Baju MLBB Nana</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 90.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 18</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 6: Pizza Triple -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Pizza Triple" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_13_5b91b1514f.jpg') }}"/>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Pizza Triple</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 50.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 15</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 7: Celana Skena -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Celana Skena" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_7_6749cc15ce.jpg') }}"/>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Celana Skena</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 100.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 10</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 8: Nasi Gila -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Nasi Gila" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_9_693f27abea.jpg') }}"/>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Nasi Gila</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 35.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 24</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 9: Burger Sapi -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Burger Sapi" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_6_bc5e7c0a44.jpg') }}"/>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Burger Sapi</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 25.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 30</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 10: Donat Glaze -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Donat Glaze" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_28_7d12f19ee8.jpg') }}"/>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Donat Glaze</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 8.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 40</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 11: Es Teh Manis -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Es Teh Manis" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_8_d98609837b.jpg') }}"/>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Es Teh Manis</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 5.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 60</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
<!-- Product Card 12: Croissant -->
<div class="border border-slate-100 rounded-2xl p-2.5 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.03)] hover:shadow-md transition-shadow flex flex-col justify-between group">
<div>
<div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100 mb-2.5">
<img alt="Croissant" class="w-full h-full object-cover" src="{{ asset('images/stitch_assets/asset_12_86bbee229a.jpg') }}"/>
</div>
<h3 class="font-bold text-xs text-slate-900 leading-tight">Croissant</h3>
<p class="text-blue-600 font-bold text-xs mt-0.5">Rp 18.000</p>
</div>
<div class="flex items-center justify-between mt-3 pt-1">
<span class="text-[10px] text-slate-400 font-normal">Stok: 20</span>
<button class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</button>
</div>
</div>
</div>
<!-- End of Catalog Label -->
<p class="text-center text-xs text-slate-400 pb-4">Semua produk sudah ditampilkan</p>
</main>
<!-- END: CatalogArea -->
<!-- BEGIN: CartPanel -->
<aside class="w-96 flex-shrink-0 flex flex-col justify-between bg-white px-6 py-6 border-l border-slate-100">
<!-- Cart Top Elements -->
<div class="flex-1 flex flex-col min-h-0">
<!-- Cart Header -->
<div class="flex items-center justify-between pb-1">
<h2 class="text-base font-bold text-slate-900">Keranjang Belanja</h2>
<span class="text-xs font-semibold px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md">3 produk</span>
</div>
<p class="text-xs text-slate-400 mb-4">Transaksi #TRX-00128</p>
<!-- Customer Dropdown Selector -->
<div class="mb-5">
<div class="flex items-center justify-between px-3.5 py-2.5 border border-slate-200 rounded-xl bg-slate-50/50 hover:bg-slate-50 transition-colors cursor-pointer">
<div class="flex items-center gap-2.5 text-slate-600 text-xs">
<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
<span class="font-medium text-slate-700">Pelanggan umum</span>
</div>
<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
</div>
<!-- Cart Items List (Scrollable) -->
<div class="flex-1 overflow-y-auto pr-1 space-y-4">
<!-- Item 1: Coffe Americano -->
<div class="space-y-2 pb-3 border-b border-slate-100">
<div class="flex items-start justify-between">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
<path d="M6 1v3M10 1v3M14 1v3" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
</div>
<div>
<h4 class="font-bold text-xs text-slate-900 leading-tight">Coffe Americano</h4>
<p class="text-[11px] text-slate-400 mt-0.5">Rp 15.000 / item</p>
</div>
</div>
<button class="text-slate-400 hover:text-red-500 transition-colors p-1">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="flex items-center justify-between pt-1">
<div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-white">
<button class="w-6 h-6 flex items-center justify-center text-slate-500 hover:bg-slate-100 text-xs font-medium">-</button>
<span class="w-6 text-center text-xs font-semibold text-slate-800">2</span>
<button class="w-6 h-6 flex items-center justify-center text-blue-600 hover:bg-slate-100 text-xs font-medium">+</button>
</div>
<span class="font-bold text-xs text-slate-900">Rp 30.000</span>
</div>
</div>
<!-- Item 2: Matcha Latte -->
<div class="space-y-2 pb-3 border-b border-slate-100">
<div class="flex items-start justify-between">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
</div>
<div>
<h4 class="font-bold text-xs text-slate-900 leading-tight">Matcha Latte</h4>
<p class="text-[11px] text-slate-400 mt-0.5">Rp 18.000 / item</p>
</div>
</div>
<button class="text-slate-400 hover:text-red-500 transition-colors p-1">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="flex items-center justify-between pt-1">
<div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-white">
<button class="w-6 h-6 flex items-center justify-center text-slate-500 hover:bg-slate-100 text-xs font-medium">-</button>
<span class="w-6 text-center text-xs font-semibold text-slate-800">1</span>
<button class="w-6 h-6 flex items-center justify-center text-blue-600 hover:bg-slate-100 text-xs font-medium">+</button>
</div>
<span class="font-bold text-xs text-slate-900">Rp 18.000</span>
</div>
</div>
<!-- Item 3: Fries -->
<div class="space-y-2 pb-3 border-b border-slate-100">
<div class="flex items-start justify-between">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M3 3v18h18M7 8h10M7 12h7" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
</div>
<div>
<h4 class="font-bold text-xs text-slate-900 leading-tight">Fries</h4>
<p class="text-[11px] text-slate-400 mt-0.5">Rp 22.000 / item</p>
</div>
</div>
<button class="text-slate-400 hover:text-red-500 transition-colors p-1">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="flex items-center justify-between pt-1">
<div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-white">
<button class="w-6 h-6 flex items-center justify-center text-slate-500 hover:bg-slate-100 text-xs font-medium">-</button>
<span class="w-6 text-center text-xs font-semibold text-slate-800">1</span>
<button class="w-6 h-6 flex items-center justify-center text-blue-600 hover:bg-slate-100 text-xs font-medium">+</button>
</div>
<span class="font-bold text-xs text-slate-900">Rp 22.000</span>
</div>
</div>
</div>
<!-- Add Transaction Note Button -->
<div class="pt-3">
<button class="flex items-center gap-1.5 text-xs text-blue-600 font-medium hover:text-blue-700">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
              Tambah catatan transaksi
            </button>
</div>
</div>
<!-- Cart Summary & Checkout Action Section -->
<div class="pt-4 border-t border-slate-100 space-y-4">
<!-- Summary Details -->
<div class="space-y-1.5 text-xs">
<div class="flex justify-between text-slate-500">
<span>Subtotal (4 item)</span>
<span class="text-slate-800 font-medium">Rp 70.000</span>
</div>
<div class="flex justify-between text-slate-500">
<span>Diskon</span>
<span class="text-slate-800 font-medium">Rp 0</span>
</div>
</div>
<!-- Total Calculation -->
<div class="flex items-baseline justify-between pt-1">
<span class="text-xs font-bold text-slate-900">Total Akhir</span>
<span class="text-xl font-bold text-blue-600">Rp 70.000</span>
</div>
<!-- Checkout & Cancel Buttons -->
<div class="space-y-2">
<button class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl flex items-center justify-center gap-2 shadow-md shadow-blue-500/20 transition-all text-xs">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
              Bayar (Cash/QRIS)
              <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
<button class="w-full py-2.5 bg-slate-100 hover:bg-slate-200/70 text-slate-700 font-medium rounded-xl transition-colors text-xs">
              Batal
            </button>
</div>
<!-- Trust & Security Note -->
<div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-400 pt-1">
<svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
<span>Pembayaran aman dengan tunai atau QRIS</span>
</div>
</div>
</aside>
<!-- END: CartPanel -->
</div>
</div>
<!-- END: ContentAreaWrapper -->
@endsection
