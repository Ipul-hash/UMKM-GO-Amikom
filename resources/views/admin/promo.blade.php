@extends('layouts.admin')

@section('title', 'UMKMGo - Promo & Diskon')

@section('content')
<!-- END: TopBar -->
<!-- BEGIN: MainBodyArea -->
<main class="p-8 max-w-[1400px] w-full mx-auto">
<!-- Top Title and CTA Section -->
<div class="flex items-center justify-between mb-8">
<div>
<h1 class="text-2xl font-bold text-slate-900 tracking-tight">Promo &amp; Diskon</h1>
<p class="text-sm text-slate-500 mt-1">Kelola voucher dan program diskon toko</p>
</div>
<button class="inline-flex items-center gap-2 bg-[#2563EB] hover:bg-blue-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg shadow-sm transition-colors" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
          Buat Promo Baru
        </button>
</div>
<!-- Search and Filter Controls -->
<div class="flex items-center gap-4 mb-7">
<!-- Search Input -->
<div class="relative w-80">
<div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<input class="w-full pl-10 pr-4 py-2 text-sm bg-white border border-slate-200 rounded-lg placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow" placeholder="Cari promo..." type="text"/>
</div>
<!-- Filter Pills Container -->
<div class="flex items-center bg-slate-100/80 p-1 rounded-xl">
<button class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-white text-blue-600 shadow-sm transition-all" type="button">Semua</button>
<button class="px-4 py-1.5 text-xs font-medium rounded-lg text-slate-500 hover:text-slate-800 transition-all" type="button">Aktif</button>
<button class="px-4 py-1.5 text-xs font-medium rounded-lg text-slate-500 hover:text-slate-800 transition-all" type="button">Berakhir</button>
</div>
</div>
<!-- Promo Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
<!-- CARD 1: Diskon Akhir Pekan 20% -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between">
<div>
<!-- Header: Icon & Status Badge -->
<div class="flex items-start justify-between mb-4">
<div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                %
              </div>
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Aktif
              </span>
</div>
<!-- Card Title -->
<h2 class="text-base font-bold text-slate-900 mb-4">Diskon Akhir Pekan 20%</h2>
<!-- Coupon Code Box -->
<div class="bg-slate-50/70 border border-dashed border-slate-200 rounded-xl p-3 mb-5 flex items-center gap-2">
<span class="text-xs text-slate-400">Kode:</span>
<span class="text-xs font-bold text-slate-700 tracking-wider">WEEKEND20</span>
</div>
<!-- Periode Info -->
<div class="mb-4">
<span class="text-[11px] text-slate-400 block mb-1">Periode</span>
<div class="flex items-center gap-2 text-xs font-medium text-slate-600">
<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
<line x1="16" x2="16" y1="2" y2="6"></line>
<line x1="8" x2="8" y1="2" y2="6"></line>
<line x1="3" x2="21" y1="10" y2="10"></line>
</svg>
                10-31 Okt 2026
              </div>
</div>
<!-- Kuota Progress -->
<div class="mb-6">
<div class="flex justify-between items-center text-xs mb-1.5">
<span class="text-slate-400">Kuota</span>
<span class="font-semibold text-slate-700">45/100</span>
</div>
<div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
<div class="bg-blue-600 h-full rounded-full" style="width: 45%;"></div>
</div>
</div>
</div>
<!-- Bottom Footer: Toggle Switch -->
<div class="pt-4 border-t border-slate-100 flex items-center justify-between">
<span class="text-xs text-slate-500 font-medium">Status promo</span>
<!-- Toggle ON -->
<label class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
</label>
</div>
</div>
<!-- CARD 2: Potongan Kopi Rp 5.000 -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between">
<div>
<!-- Header: Icon & Status Badge -->
<div class="flex items-start justify-between mb-4">
<div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
<!-- Coffee Cup Icon -->
<svg class="w-6 h-6 stroke-blue-600" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-500">
<span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                Berakhir
              </span>
</div>
<!-- Card Title -->
<h2 class="text-base font-bold text-slate-900 mb-4">Potongan Kopi Rp 5.000</h2>
<!-- Coupon Code Box -->
<div class="bg-slate-50/70 border border-dashed border-slate-200 rounded-xl p-3 mb-5 flex items-center gap-2">
<span class="text-xs text-slate-400">Kode:</span>
<span class="text-xs font-bold text-slate-700 tracking-wider">KOPIHEMAT</span>
</div>
<!-- Periode Info -->
<div class="mb-4">
<span class="text-[11px] text-slate-400 block mb-1">Periode</span>
<div class="flex items-center gap-2 text-xs font-medium text-slate-600">
<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
<line x1="16" x2="16" y1="2" y2="6"></line>
<line x1="8" x2="8" y1="2" y2="6"></line>
<line x1="3" x2="21" y1="10" y2="10"></line>
</svg>
                1-15 Okt 2026
              </div>
</div>
<!-- Kuota Progress -->
<div class="mb-6">
<div class="flex justify-between items-center text-xs mb-1.5">
<span class="text-slate-400">Kuota</span>
<span class="font-semibold text-slate-700">100/100</span>
</div>
<div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
<div class="bg-slate-300 h-full rounded-full" style="width: 100%;"></div>
</div>
</div>
</div>
<!-- Bottom Footer: Toggle Switch -->
<div class="pt-4 border-t border-slate-100 flex items-center justify-between">
<span class="text-xs text-slate-500 font-medium">Status promo</span>
<!-- Toggle OFF -->
<label class="relative inline-flex items-center cursor-pointer">
<input class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
</label>
</div>
</div>
<!-- CARD 3: Buy 1 Get 1 Matcha -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between">
<div>
<!-- Header: Icon & Status Badge -->
<div class="flex items-start justify-between mb-4">
<div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
<!-- Gift Box Icon -->
<svg class="w-6 h-6 stroke-blue-600" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<path d="M12 8v13m0-13V4.5a2.5 2.5 0 10-5 0V8m5 0h8.5A1.5 1.5 0 0122 9.5v2a1.5 1.5 0 01-1.5 1.5H12m0-5H3.5A1.5 1.5 0 002 9.5v2a1.5 1.5 0 001.5 1.5H12m0 0v8.5a1.5 1.5 0 001.5 1.5h7a1.5 1.5 0 001.5-1.5V13m-10 0H3.5a1.5 1.5 0 00-1.5 1.5v5.5A1.5 1.5 0 003.5 21.5h7a1.5 1.5 0 001.5-1.5V13" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Aktif
              </span>
</div>
<!-- Card Title -->
<h2 class="text-base font-bold text-slate-900 mb-4">Buy 1 Get 1 Matcha</h2>
<!-- Coupon Code Box -->
<div class="bg-slate-50/70 border border-dashed border-slate-200 rounded-xl p-3 mb-5 flex items-center gap-2">
<span class="text-xs text-slate-400">Kode:</span>
<span class="text-xs font-bold text-slate-700 tracking-wider">MATCHAMANIA</span>
</div>
<!-- Periode Info -->
<div class="mb-4">
<span class="text-[11px] text-slate-400 block mb-1">Periode</span>
<div class="flex items-center gap-2 text-xs font-medium text-slate-600">
<svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
<line x1="16" x2="16" y1="2" y2="6"></line>
<line x1="8" x2="8" y1="2" y2="6"></line>
<line x1="3" x2="21" y1="10" y2="10"></line>
</svg>
                12-20 Okt 2026
              </div>
</div>
<!-- Kuota Progress -->
<div class="mb-6">
<div class="flex justify-between items-center text-xs mb-1.5">
<span class="text-slate-400">Kuota</span>
<span class="font-semibold text-slate-700">12/50</span>
</div>
<div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
<div class="bg-blue-600 h-full rounded-full" style="width: 24%;"></div>
</div>
</div>
</div>
<!-- Bottom Footer: Toggle Switch -->
<div class="pt-4 border-t border-slate-100 flex items-center justify-between">
<span class="text-xs text-slate-500 font-medium">Status promo</span>
<!-- Toggle ON -->
<label class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
</label>
</div>
</div>
</div>
<!-- END: Promo Cards Grid -->
<!-- BEGIN: PaginationFooter -->
<div class="flex items-center justify-between pt-2">
<p class="text-xs text-slate-500 font-medium">Menampilkan 3 promo</p>
<div class="flex items-center gap-1.5 select-none">
<!-- Previous Arrow -->
<button aria-label="Previous Page" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
<!-- Page 1 (Active) -->
<button class="w-8 h-8 rounded-lg bg-blue-600 text-white font-medium text-xs flex items-center justify-center shadow-sm" type="button">
            1
          </button>
<!-- Page 2 -->
<button class="w-8 h-8 rounded-lg text-slate-600 hover:bg-slate-100 font-medium text-xs flex items-center justify-center transition-colors" type="button">
            2
          </button>
<!-- Next Arrow -->
<button aria-label="Next Page" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</button>
</div>
</div>
<!-- END: PaginationFooter -->
</main>
<!-- END: MainBodyArea -->
</div>
<!-- END: MainContentWrapper -->
</body></html>
@endsection
