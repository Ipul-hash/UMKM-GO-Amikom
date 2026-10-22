@extends('layouts.admin')

@section('title', 'UMKMGo - Laporan Penjualan')

@section('content')
<!-- END: TopBar -->
<!-- BEGIN: Dashboard Body Content -->
<main class="p-8 space-y-6">
<!-- BEGIN: Content Header Section -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
<div>
<h1 class="text-2xl font-bold tracking-tight text-gray-900">Laporan Penjualan</h1>
<p class="text-sm text-gray-500 mt-1">Analisis performa bisnis dan penjualan</p>
</div>
<div class="flex items-center gap-3 shrink-0">
<!-- Date Filter Picker -->
<div class="flex items-center gap-2.5 bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-700 shadow-sm cursor-pointer hover:border-gray-300">
<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
<line x1="16" x2="16" y1="2" y2="6"></line>
<line x1="8" x2="8" y1="2" y2="6"></line>
<line x1="3" x2="21" y1="10" y2="10"></line>
</svg>
<span>01 Okt 2026 - 31 Okt 2026</span>
<svg class="w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<!-- Export Button -->
<button class="flex items-center gap-2 bg-[#2563EB] hover:bg-blue-700 text-white font-medium text-xs px-4 py-2.5 rounded-xl shadow-sm transition-colors" type="button">
<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
            Export PDF/Excel
          </button>
</div>
</div>
<!-- END: Content Header Section -->
<!-- BEGIN: Revenue Trend Card -->
<section class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
<div class="flex items-start justify-between">
<div>
<span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Tren Omzet Harian</span>
<div class="flex items-center gap-3 mt-1.5">
<span class="text-2xl font-bold text-gray-900 tracking-tight">Rp 24.850.000</span>
<span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-xs font-bold px-2 py-0.5 rounded-md">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewbox="0 0 24 24">
<path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
                +12% vs bulan lalu
              </span>
</div>
</div>
<!-- Legend Indicator -->
<div class="flex items-center gap-2 text-xs font-medium text-gray-500">
<span class="w-2 h-2 rounded-full bg-blue-600"></span>
            Omzet harian
          </div>
</div>
<!-- Trend Line SVG Chart -->
<div class="mt-6 w-full h-[180px]">
<svg class="w-full h-full overflow-visible" preserveaspectratio="none" viewbox="0 0 1000 180">
<defs>
<lineargradient id="gradientOmzet" x1="0" x2="0" y1="0" y2="1">
<stop offset="0%" stop-color="#2563EB" stop-opacity="0.08"></stop>
<stop offset="100%" stop-color="#2563EB" stop-opacity="0.0"></stop>
</lineargradient>
</defs>
<!-- Area Gradient Fill -->
<path d="M 0 180 
                     L 0 170 
                     C 25 140, 35 130, 50 135 
                     C 65 140, 80 160, 95 155 
                     C 110 150, 125 130, 145 130 
                     C 165 130, 175 145, 190 140 
                     C 205 135, 215 118, 230 115 
                     C 245 112, 260 135, 275 130 
                     C 290 125, 295 145, 310 145 
                     C 325 145, 335 115, 355 105 
                     C 375 95, 385 115, 405 110 
                     C 425 105, 435 125, 450 125 
                     C 465 125, 475 98, 490 98 
                     C 505 98, 515 112, 530 110 
                     C 545 108, 555 130, 570 125 
                     C 585 120, 595 100, 615 100 
                     C 635 100, 645 110, 660 105 
                     C 675 100, 685 85, 705 85 
                     C 725 85, 735 102, 755 100 
                     C 775 98, 785 88, 800 88 
                     C 815 88, 825 98, 840 98 
                     C 855 98, 865 75, 885 75 
                     C 905 75, 915 90, 930 85 
                     C 945 80, 955 75, 970 78 
                     C 985 80, 990 60, 1000 55 
                     L 1000 180 Z" fill="url(#gradientOmzet)"></path>
<!-- Continuous Trend Curve -->
<path d="M 0 170 
                     C 25 140, 35 130, 50 135 
                     C 65 140, 80 160, 95 155 
                     C 110 150, 125 130, 145 130 
                     C 165 130, 175 145, 190 140 
                     C 205 135, 215 118, 230 115 
                     C 245 112, 260 135, 275 130 
                     C 290 125, 295 145, 310 145 
                     C 325 145, 335 115, 355 105 
                     C 375 95, 385 115, 405 110 
                     C 425 105, 435 125, 450 125 
                     C 465 125, 475 98, 490 98 
                     C 505 98, 515 112, 530 110 
                     C 545 108, 555 130, 570 125 
                     C 585 120, 595 100, 615 100 
                     C 635 100, 645 110, 660 105 
                     C 675 100, 685 85, 705 85 
                     C 725 85, 735 102, 755 100 
                     C 775 98, 785 88, 800 88 
                     C 815 88, 825 98, 840 98 
                     C 855 98, 865 75, 885 75 
                     C 905 75, 915 90, 930 85 
                     C 945 80, 955 75, 970 78 
                     C 985 80, 990 60, 1000 55" fill="none" stroke="#2563EB" stroke-linecap="round" stroke-width="2.5"></path>
</svg>
</div>
<!-- Date Axis Labels -->
<div class="flex justify-between items-center text-[11px] text-gray-400 font-medium pt-3 border-t border-gray-100">
<span>01 Okt</span>
<span>06 Okt</span>
<span>11 Okt</span>
<span>16 Okt</span>
<span>21 Okt</span>
<span>26 Okt</span>
<span>31 Okt</span>
</div>
<!-- Chart Footnote -->
<p class="text-[11px] text-gray-400 mt-2">Grafik ilustratif · Nilai harian tidak ditampilkan.</p>
</section>
<!-- END: Revenue Trend Card -->
<!-- BEGIN: Bottom Analytics Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<!-- Top Selling Products Card -->
<section class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm flex flex-col justify-between">
<div>
<h3 class="text-sm font-bold text-gray-900 tracking-tight mb-4">5 Produk Terlaris</h3>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead>
<tr class="bg-gray-50/80 text-[11px] font-semibold text-gray-500 uppercase tracking-wider rounded-lg">
<th class="py-2.5 px-3 rounded-l-lg w-12 text-center">No.</th>
<th class="py-2.5 px-3">Nama produk</th>
<th class="py-2.5 px-3 text-right rounded-r-lg">Terjual</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-100 text-xs">
<!-- Row 1 -->
<tr>
<td class="py-3 px-3 text-center">
<span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 text-blue-600 font-bold text-[11px]">1</span>
</td>
<td class="py-3 px-3 font-semibold text-gray-800">Coffe Americano</td>
<td class="py-3 px-3 text-right font-bold text-gray-900">142 porsi</td>
</tr>
<!-- Row 2 -->
<tr>
<td class="py-3 px-3 text-center">
<span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-gray-100 text-gray-500 font-bold text-[11px]">2</span>
</td>
<td class="py-3 px-3 font-semibold text-gray-800">Matcha Latte</td>
<td class="py-3 px-3 text-right font-bold text-gray-900">98 porsi</td>
</tr>
<!-- Row 3 -->
<tr>
<td class="py-3 px-3 text-center">
<span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-gray-100 text-gray-500 font-bold text-[11px]">3</span>
</td>
<td class="py-3 px-3 font-semibold text-gray-800">Fries</td>
<td class="py-3 px-3 text-right font-bold text-gray-900">75 porsi</td>
</tr>
</tbody>
</table>
</div>
</div>
<div class="pt-4 border-t border-gray-100 mt-6 text-[11px] text-gray-400">
            Menampilkan 3 produk
          </div>
</section>
<!-- Payment Methods Card -->
<section class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm flex flex-col justify-between">
<div>
<h3 class="text-sm font-bold text-gray-900 tracking-tight mb-4">Metode Pembayaran</h3>
<div class="flex items-center justify-around gap-6 pt-2">
<!-- Circular Donut Chart -->
<div class="relative w-40 h-40 flex items-center justify-center">
<div class="w-full h-full rounded-full donut-chart"></div>
<!-- Inner cutout for donut style -->
<div class="absolute w-28 h-28 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
<span class="text-xl font-bold text-gray-900 leading-tight">100%</span>
<span class="text-[10px] text-gray-400 font-medium">Pembayaran</span>
</div>
</div>
<!-- Payment Legend & Stats -->
<div class="space-y-3.5 min-w-[150px]">
<!-- QRIS -->
<div class="flex items-center justify-between text-xs">
<div class="flex items-center gap-2.5">
<span class="w-2.5 h-2.5 rounded-sm bg-[#2563EB]"></span>
<span class="text-gray-600 font-medium">QRIS</span>
</div>
<span class="font-bold text-gray-900 ml-4">45%</span>
</div>
<!-- Tunai -->
<div class="flex items-center justify-between text-xs">
<div class="flex items-center gap-2.5">
<span class="w-2.5 h-2.5 rounded-sm bg-[#60A5FA]"></span>
<span class="text-gray-600 font-medium">Tunai</span>
</div>
<span class="font-bold text-gray-900 ml-4">30%</span>
</div>
<!-- Transfer BCA -->
<div class="flex items-center justify-between text-xs">
<div class="flex items-center gap-2.5">
<span class="w-2.5 h-2.5 rounded-sm bg-[#BFDBFE]"></span>
<span class="text-gray-600 font-medium">Transfer BCA</span>
</div>
<span class="font-bold text-gray-900 ml-4">25%</span>
</div>
</div>
</div>
</div>
<!-- Empty space filler to harmonize height with left card -->
<div class="h-4"></div>
</section>
</div>
<!-- END: Bottom Analytics Grid -->
</main>
<!-- END: Dashboard Body Content -->
</div>
<!-- END: Main Content Area -->
</body></html>
@endsection
