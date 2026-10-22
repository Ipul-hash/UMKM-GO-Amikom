@extends('layouts.admin')

@section('title', 'UMKMGo - Supplier / Pemasok')

@section('content')
<!-- END: TopNavbar -->
<!-- BEGIN: MainContent -->
<main class="flex-1 p-8 overflow-y-auto">
<div class="max-w-[1240px] mx-auto space-y-6">
<!-- Header Section: Title & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
<div>
<h1 class="text-2xl font-bold text-slate-900 tracking-tight">Supplier / Pemasok</h1>
<p class="text-[13.5px] text-slate-500 mt-1">Daftar vendor bahan baku dan produk</p>
</div>
<!-- Add Supplier CTA -->
<button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-[13.5px] px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition-all">
<svg class="w-4 h-4 stroke-[2.2] stroke-current" fill="none" viewbox="0 0 24 24">
<line x1="12" x2="12" y1="5" y2="19"></line>
<line x1="5" x2="19" y1="12" y2="12"></line>
</svg>
<span>Tambah Supplier</span>
</button>
</div>
<!-- Filter & Search Bar Section -->
<div class="flex items-center justify-between pt-2">
<!-- Search Input Box -->
<div class="relative w-72">
<span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
<svg class="w-4 h-4 stroke-[2] stroke-current" fill="none" viewbox="0 0 24 24">
<circle cx="11" cy="11" r="8"></circle>
<line x1="21" x2="16.65" y1="21" y2="16.65"></line>
</svg>
</span>
<input class="w-full pl-10 pr-4 py-2 rounded-lg bg-white border border-slate-200 text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-xs" placeholder="Cari supplier..." type="text"/>
</div>
<!-- Total Counter -->
<div class="text-[13px] text-slate-500 font-medium">
            3 supplier
          </div>
</div>
<!-- BEGIN: SupplierCardsGrid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 pt-1">
<!-- Card 1: PT Biji Kopi Nusantara -->
<div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
<div>
<!-- Header info with Coffee Icon -->
<div class="flex items-start gap-3.5">
<div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 text-blue-600">
<svg class="w-6 h-6 stroke-[1.8] stroke-current" fill="none" viewbox="0 0 24 24">
<path d="M18 8h1a4 4 0 0 1 0 8h-1" stroke-linecap="round" stroke-linejoin="round"></path>
<path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" stroke-linecap="round" stroke-linejoin="round"></path>
<line x1="6" x2="6" y1="1" y2="4"></line>
<line x1="10" x2="10" y1="1" y2="4"></line>
<line x1="14" x2="14" y1="1" y2="4"></line>
</svg>
</div>
<div>
<h3 class="text-[15px] font-bold text-slate-900 leading-snug">PT Biji Kopi Nusantara</h3>
<p class="text-[12.5px] text-slate-500 mt-0.5">Pasokan: Biji Kopi</p>
</div>
</div>
<!-- Thin divider -->
<div class="border-t border-slate-100 my-4"></div>
<!-- Contact & PIC details -->
<div class="space-y-2.5 text-[13px]">
<div class="text-[11.5px] font-medium text-slate-400">PIC / Penanggung jawab</div>
<div class="flex items-center gap-2.5 text-slate-700">
<svg class="w-4 h-4 text-slate-400 stroke-[1.8] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"></path>
<circle cx="12" cy="7" r="4"></circle>
</svg>
<span class="font-medium text-slate-800">Pak Haryanto</span>
</div>
<div class="flex items-center gap-2.5 text-slate-600">
<svg class="w-4 h-4 text-slate-400 stroke-[1.8] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
<span>0812-9988-7766</span>
</div>
<div class="flex items-center gap-2.5 text-slate-600">
<svg class="w-4 h-4 text-slate-400 stroke-[1.8] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke-linecap="round" stroke-linejoin="round"></path>
<circle cx="12" cy="10" r="3"></circle>
</svg>
<span>Bandung</span>
</div>
</div>
</div>
<!-- Action Button -->
<button class="mt-5 w-full py-2 px-3 border border-blue-600 text-blue-600 hover:bg-blue-50 active:bg-blue-100 rounded-lg text-[13px] font-semibold flex items-center justify-center gap-2 transition-colors">
<svg class="w-4 h-4 stroke-[2] stroke-current" fill="none" viewbox="0 0 24 24">
<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-linecap="round" stroke-linejoin="round"></path>
<polyline points="14 2 14 8 20 8"></polyline>
<line x1="12" x2="12" y1="18" y2="12"></line>
<line x1="9" x2="15" y1="15" y2="15"></line>
</svg>
<span>Buat PO</span>
</button>
</div>
<!-- Card 2: CV Packaging Murah -->
<div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
<div>
<!-- Header info with Box Icon -->
<div class="flex items-start gap-3.5">
<div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 text-blue-600">
<svg class="w-6 h-6 stroke-[1.8] stroke-current" fill="none" viewbox="0 0 24 24">
<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" stroke-linecap="round" stroke-linejoin="round"></path>
<polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
<line x1="12" x2="12" y1="22.08" y2="12"></line>
</svg>
</div>
<div>
<h3 class="text-[15px] font-bold text-slate-900 leading-snug">CV Packaging Murah</h3>
<p class="text-[12.5px] text-slate-500 mt-0.5">Pasokan: Cup &amp; Kemasan</p>
</div>
</div>
<!-- Thin divider -->
<div class="border-t border-slate-100 my-4"></div>
<!-- Contact & PIC details -->
<div class="space-y-2.5 text-[13px]">
<div class="text-[11.5px] font-medium text-slate-400">PIC / Penanggung jawab</div>
<div class="flex items-center gap-2.5 text-slate-700">
<svg class="w-4 h-4 text-slate-400 stroke-[1.8] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"></path>
<circle cx="12" cy="7" r="4"></circle>
</svg>
<span class="font-medium text-slate-800">Ibu Ratna</span>
</div>
<div class="flex items-center gap-2.5 text-slate-600">
<svg class="w-4 h-4 text-slate-400 stroke-[1.8] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
<span>0813-4455-6677</span>
</div>
<div class="flex items-center gap-2.5 text-slate-600">
<svg class="w-4 h-4 text-slate-400 stroke-[1.8] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke-linecap="round" stroke-linejoin="round"></path>
<circle cx="12" cy="10" r="3"></circle>
</svg>
<span>Jakarta</span>
</div>
</div>
</div>
<!-- Action Button -->
<button class="mt-5 w-full py-2 px-3 border border-blue-600 text-blue-600 hover:bg-blue-50 active:bg-blue-100 rounded-lg text-[13px] font-semibold flex items-center justify-center gap-2 transition-colors">
<svg class="w-4 h-4 stroke-[2] stroke-current" fill="none" viewbox="0 0 24 24">
<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-linecap="round" stroke-linejoin="round"></path>
<polyline points="14 2 14 8 20 8"></polyline>
<line x1="12" x2="12" y1="18" y2="12"></line>
<line x1="9" x2="15" y1="15" y2="15"></line>
</svg>
<span>Buat PO</span>
</button>
</div>
<!-- Card 3: Distro Fashion Grosir -->
<div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
<div>
<!-- Header info with Shirt Icon -->
<div class="flex items-start gap-3.5">
<div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 text-blue-600">
<svg class="w-6 h-6 stroke-[1.8] stroke-current" fill="none" viewbox="0 0 24 24">
<path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<h3 class="text-[15px] font-bold text-slate-900 leading-snug">Distro Fashion Grosir</h3>
<p class="text-[12.5px] text-slate-500 mt-0.5">Pasokan: Pakaian &amp; Tas</p>
</div>
</div>
<!-- Thin divider -->
<div class="border-t border-slate-100 my-4"></div>
<!-- Contact & PIC details -->
<div class="space-y-2.5 text-[13px]">
<div class="text-[11.5px] font-medium text-slate-400">PIC / Penanggung jawab</div>
<div class="flex items-center gap-2.5 text-slate-700">
<svg class="w-4 h-4 text-slate-400 stroke-[1.8] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-linejoin="round"></path>
<circle cx="12" cy="7" r="4"></circle>
</svg>
<span class="font-medium text-slate-800">Mas Eko</span>
</div>
<div class="flex items-center gap-2.5 text-slate-600">
<svg class="w-4 h-4 text-slate-400 stroke-[1.8] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
<span>0857-2233-4455</span>
</div>
<div class="flex items-center gap-2.5 text-slate-600">
<svg class="w-4 h-4 text-slate-400 stroke-[1.8] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke-linecap="round" stroke-linejoin="round"></path>
<circle cx="12" cy="10" r="3"></circle>
</svg>
<span>Solo</span>
</div>
</div>
</div>
<!-- Action Button -->
<button class="mt-5 w-full py-2 px-3 border border-blue-600 text-blue-600 hover:bg-blue-50 active:bg-blue-100 rounded-lg text-[13px] font-semibold flex items-center justify-center gap-2 transition-colors">
<svg class="w-4 h-4 stroke-[2] stroke-current" fill="none" viewbox="0 0 24 24">
<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-linecap="round" stroke-linejoin="round"></path>
<polyline points="14 2 14 8 20 8"></polyline>
<line x1="12" x2="12" y1="18" y2="12"></line>
<line x1="9" x2="15" y1="15" y2="15"></line>
</svg>
<span>Buat PO</span>
</button>
</div>
</div>
<!-- END: SupplierCardsGrid -->
</div>
</main>
<!-- END: MainContent -->
</div>
<!-- END: MainContentArea -->
</body></html>
@endsection
