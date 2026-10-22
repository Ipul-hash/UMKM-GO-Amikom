@extends('layouts.admin')

@section('title', 'UMKMGo - Pesanan Online')

@section('content')
<!-- END: TopBar -->
<!-- BEGIN: MainContentArea -->
<main class="p-8 max-w-[1300px] w-full mx-auto" data-purpose="main-layout">
<!-- Title & Subtitle Section -->
<div class="mb-6">
<h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pesanan Online</h1>
<p class="text-gray-500 text-sm mt-1">Pantau dan proses pesanan dari online delivery dan website</p>
</div>
<!-- Horizontal Tabs Navigation -->
<div class="border-b border-gray-200 mb-6 flex items-center gap-8 text-sm">
<!-- Tab: Semua (Active) -->
<button class="font-semibold text-blue-600 pb-3 border-b-2 border-blue-600 px-1">
          Semua
        </button>
<!-- Tab: Pesanan Baru -->
<button class="font-medium text-gray-500 hover:text-gray-800 pb-3 flex items-center gap-2 px-1 transition-colors">
<span>Pesanan Baru</span>
<span class="bg-red-50 text-red-600 font-bold px-2 py-0.5 rounded-full text-xs">(3)</span>
</button>
<!-- Tab: Diproses -->
<button class="font-medium text-gray-500 hover:text-gray-800 pb-3 flex items-center gap-2 px-1 transition-colors">
<span>Diproses</span>
<span class="bg-blue-50 text-blue-600 font-semibold px-2 py-0.5 rounded-full text-xs">(5)</span>
</button>
<!-- Tab: Dalam Pengiriman -->
<button class="font-medium text-gray-500 hover:text-gray-800 pb-3 flex items-center gap-2 px-1 transition-colors">
<span>Dalam Pengiriman</span>
<span class="bg-gray-100 text-gray-600 font-semibold px-2 py-0.5 rounded-full text-xs">(2)</span>
</button>
<!-- Tab: Selesai -->
<button class="font-medium text-gray-500 hover:text-gray-800 pb-3 px-1 transition-colors">
          Selesai
        </button>
<!-- Tab: Dibatalkan -->
<button class="font-medium text-gray-500 hover:text-gray-800 pb-3 px-1 transition-colors">
          Dibatalkan
        </button>
</div>
<!-- Filter and Search Row -->
<div class="flex items-center justify-between gap-4 mb-6">
<div class="flex items-center gap-3 flex-1 max-w-2xl">
<!-- Search Bar -->
<div class="relative flex-1">
<span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</span>
<input class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Cari nomor pesanan, nama pelanggan..." type="text"/>
</div>
<!-- Dropdown Courier Filter -->
<button class="bg-white border border-gray-200 px-4 py-2 rounded-xl text-sm font-medium text-gray-700 flex items-center gap-2 hover:bg-gray-50 flex-shrink-0">
<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
<span>Semua Kurir</span>
<svg class="w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
<!-- Dropdown Date Filter -->
<button class="bg-white border border-gray-200 px-4 py-2 rounded-xl text-sm font-medium text-gray-700 flex items-center gap-2 hover:bg-gray-50 flex-shrink-0">
<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
<span>Tanggal</span>
<svg class="w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<!-- Status Right Indication -->
<div class="flex items-center gap-2 text-xs font-medium text-gray-600">
<span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
<span>Pesanan hari ini</span>
</div>
</div>
<!-- BEGIN: OrderListCards -->
<div class="space-y-3.5" data-purpose="orders-list">
<!-- Card 1: #ORD-0092 -->
<div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center justify-between shadow-xs hover:border-gray-300 transition-all">
<div class="flex items-center gap-4 w-[280px]">
<img alt="Iced Coffee" class="w-14 h-14 rounded-xl object-cover flex-shrink-0" src="{{ asset('images/stitch_assets/asset_21_ebd117effc.jpg') }}"/>
<div>
<div class="flex items-center gap-2">
<span class="font-bold text-gray-900 text-base">#ORD-0092</span>
<span class="bg-emerald-50 text-emerald-600 text-xs px-2 py-0.5 rounded-full font-semibold flex items-center gap-1">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Baru
                </span>
</div>
<div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium mt-1">
<span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
<span>GoFood</span>
</div>
</div>
</div>
<!-- Items Ordered -->
<div class="w-[240px]">
<p class="text-sm font-medium text-gray-700">2x Coffe Americano, 1x Fries</p>
</div>
<!-- Customer Name -->
<div class="w-[140px]">
<span class="block text-[11px] text-gray-400 font-medium">Pembeli</span>
<p class="text-sm font-bold text-gray-900 leading-tight">Andi Saputra</p>
</div>
<!-- Total Price -->
<div class="w-[120px]">
<span class="block text-[11px] text-gray-400 font-medium">Total</span>
<p class="text-base font-bold text-gray-900 leading-tight">Rp 52.000</p>
</div>
<!-- Actions -->
<div class="flex items-center gap-2.5">
<button class="px-4 py-2 border border-gray-300 hover:bg-gray-50 rounded-xl text-xs font-semibold text-gray-700 transition-colors">
              Tolak
            </button>
<button class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-xs font-semibold text-white shadow-sm transition-colors">
              Terima &amp; Proses
            </button>
</div>
</div>
<!-- Card 2: #ORD-0091 -->
<div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center justify-between shadow-xs hover:border-gray-300 transition-all">
<div class="flex items-center gap-4 w-[280px]">
<img alt="Matcha Drink" class="w-14 h-14 rounded-xl object-cover flex-shrink-0" src="{{ asset('images/stitch_assets/asset_11_8e082c9ea5.jpg') }}"/>
<div>
<div class="flex items-center gap-2">
<span class="font-bold text-gray-900 text-base">#ORD-0091</span>
<span class="bg-emerald-50 text-emerald-600 text-xs px-2 py-0.5 rounded-full font-semibold flex items-center gap-1">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Baru
                </span>
</div>
<div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium mt-1">
<span class="w-2 h-2 rounded-full bg-orange-500 inline-block"></span>
<span>ShopeeFood</span>
</div>
</div>
</div>
<!-- Items Ordered -->
<div class="w-[240px]">
<p class="text-sm font-medium text-gray-700">3x Matcha Latte</p>
</div>
<!-- Customer Name -->
<div class="w-[140px]">
<span class="block text-[11px] text-gray-400 font-medium">Pembeli</span>
<p class="text-sm font-bold text-gray-900 leading-tight">Budi Hartono</p>
</div>
<!-- Total Price -->
<div class="w-[120px]">
<span class="block text-[11px] text-gray-400 font-medium">Total</span>
<p class="text-base font-bold text-gray-900 leading-tight">Rp 54.000</p>
</div>
<!-- Actions -->
<div class="flex items-center gap-2.5">
<button class="px-4 py-2 border border-gray-300 hover:bg-gray-50 rounded-xl text-xs font-semibold text-gray-700 transition-colors">
              Tolak
            </button>
<button class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-xs font-semibold text-white shadow-sm transition-colors">
              Terima &amp; Proses
            </button>
</div>
</div>
<!-- Card 3: #ORD-0090 -->
<div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center justify-between shadow-xs hover:border-gray-300 transition-all">
<div class="flex items-center gap-4 w-[280px]">
<img alt="T-Shirt &amp; Bag" class="w-14 h-14 rounded-xl object-cover flex-shrink-0" src="{{ asset('images/stitch_assets/asset_16_9263b4ef64.jpg') }}"/>
<div>
<div class="flex items-center gap-2">
<span class="font-bold text-gray-900 text-base">#ORD-0090</span>
<span class="bg-blue-50 text-blue-600 text-xs px-2 py-0.5 rounded-full font-semibold flex items-center gap-1">
<span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                  Diproses
                </span>
</div>
<div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium mt-1">
<span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
<span>GrabFood</span>
</div>
</div>
</div>
<!-- Items Ordered -->
<div class="w-[240px]">
<p class="text-sm font-medium text-gray-700">1x T-Shirt Hitam, 1x Tas Anyaman</p>
</div>
<!-- Customer Name -->
<div class="w-[140px]">
<span class="block text-[11px] text-gray-400 font-medium">Pembeli</span>
<p class="text-sm font-bold text-gray-900 leading-tight">Citra Kirana</p>
</div>
<!-- Total Price -->
<div class="w-[120px]">
<span class="block text-[11px] text-gray-400 font-medium">Total</span>
<p class="text-base font-bold text-gray-900 leading-tight">Rp 175.000</p>
</div>
<!-- Actions -->
<div class="flex items-center">
<button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-xs font-semibold text-white shadow-sm transition-colors">
              Kirim Pesanan
            </button>
</div>
</div>
<!-- Card 4: #ORD-0089 -->
<div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center justify-between shadow-xs hover:border-gray-300 transition-all">
<div class="flex items-center gap-4 w-[280px]">
<img alt="Burger" class="w-14 h-14 rounded-xl object-cover flex-shrink-0" src="{{ asset('images/stitch_assets/asset_14_546fc22e5d.jpg') }}"/>
<div>
<div class="flex items-center gap-2">
<span class="font-bold text-gray-900 text-base">#ORD-0089</span>
<span class="bg-orange-50 text-orange-600 text-xs px-2 py-0.5 rounded-full font-semibold flex items-center gap-1">
<span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                  Pengiriman
                </span>
</div>
<div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium mt-1">
<span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
<span>GoFood</span>
</div>
</div>
</div>
<!-- Items Ordered -->
<div class="w-[240px]">
<p class="text-sm font-medium text-gray-700">1x Burger Sapi, 2x Donat Glaze</p>
</div>
<!-- Customer Name -->
<div class="w-[140px]">
<span class="block text-[11px] text-gray-400 font-medium">Pembeli</span>
<p class="text-sm font-bold text-gray-900 leading-tight">Dini Lestari</p>
</div>
<!-- Total Price -->
<div class="w-[120px]">
<span class="block text-[11px] text-gray-400 font-medium">Total</span>
<p class="text-base font-bold text-gray-900 leading-tight">Rp 41.000</p>
</div>
<!-- Actions -->
<div class="flex items-center">
<button class="px-8 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-xl text-xs font-semibold text-white shadow-sm transition-colors">
              Selesai
            </button>
</div>
</div>
<!-- Card 5: #ORD-0088 -->
<div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center justify-between shadow-xs hover:border-gray-300 transition-all">
<div class="flex items-center gap-4 w-[280px]">
<img alt="Iced Coffee" class="w-14 h-14 rounded-xl object-cover flex-shrink-0" src="{{ asset('images/stitch_assets/asset_26_993574324e.jpg') }}"/>
<div>
<div class="flex items-center gap-2">
<span class="font-bold text-gray-900 text-base">#ORD-0088</span>
<span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full font-semibold flex items-center gap-1">
<span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                  Selesai
                </span>
</div>
<div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium mt-1">
<span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
<span>WhatsApp</span>
</div>
</div>
</div>
<!-- Items Ordered -->
<div class="w-[240px]">
<p class="text-sm font-medium text-gray-700">1x Coffe Americano</p>
</div>
<!-- Customer Name -->
<div class="w-[140px]">
<span class="block text-[11px] text-gray-400 font-medium">Pembeli</span>
<p class="text-sm font-bold text-gray-900 leading-tight">Edo Pratama</p>
</div>
<!-- Total Price -->
<div class="w-[120px]">
<span class="block text-[11px] text-gray-400 font-medium">Total</span>
<p class="text-base font-bold text-gray-900 leading-tight">Rp 15.000</p>
</div>
<!-- Actions -->
<div class="flex items-center">
<button class="px-6 py-2 border border-gray-300 hover:bg-gray-50 rounded-xl text-xs font-semibold text-gray-700 transition-colors">
              Lihat Detail
            </button>
</div>
</div>
</div>
<!-- END: OrderListCards -->
<!-- BEGIN: PaginationFooter -->
<div class="flex items-center justify-between mt-8 text-sm text-gray-500" data-purpose="pagination-controls">
<p class="font-normal text-xs text-gray-500">Menampilkan 1-5 dari 12 pesanan hari ini</p>
<div class="flex items-center gap-1">
<!-- Previous Button -->
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-gray-600 hover:bg-gray-50 disabled:opacity-50 transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
<!-- Page 1 (Active) -->
<button class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-600 text-white font-semibold text-xs shadow-xs">
            1
          </button>
<!-- Page 2 -->
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 font-medium text-xs hover:bg-gray-50 transition-colors">
            2
          </button>
<!-- Page 3 -->
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 font-medium text-xs hover:bg-gray-50 transition-colors">
            3
          </button>
<!-- Next Button -->
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
</div>
<!-- END: PaginationFooter -->
</main>
@endsection
