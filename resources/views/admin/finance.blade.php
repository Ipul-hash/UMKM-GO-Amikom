@extends('layouts.admin')

@section('title', 'UMKMGo - Keuangan')

@section('content')
<!-- END: Topbar -->
<!-- BEGIN: MainContent -->
<main class="p-8 space-y-6" data-purpose="dashboard-content">
<!-- Header Section with Filters & Actions -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
<div>
<h1 class="text-2xl font-bold tracking-tight text-slate-900 leading-none">Keuangan</h1>
<p class="text-sm text-slate-500 mt-1.5 font-normal">Pantau arus kas, pendapatan, dan pengeluaran</p>
</div>
<div class="flex items-center gap-3">
<!-- Periode Dropdown -->
<button class="bg-white border border-slate-200 text-slate-700 px-3.5 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 hover:bg-slate-50 shadow-sm transition">
<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<rect height="18" rx="2" ry="2" stroke-width="1.8" width="18" x="3" y="4"></rect>
<line stroke-width="1.8" x1="16" x2="16" y1="2" y2="6"></line>
<line stroke-width="1.8" x1="8" x2="8" y1="2" y2="6"></line>
<line stroke-width="1.8" x1="3" x2="21" y1="10" y2="10"></line>
</svg>
<span>Bulan Ini (Okt 2026)</span>
<svg class="w-3.5 h-3.5 text-slate-400 ml-1" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
<!-- Rekening Dropdown -->
<button class="bg-white border border-slate-200 text-slate-700 px-3.5 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 hover:bg-slate-50 shadow-sm transition">
<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<rect height="14" rx="2" stroke-width="1.8" width="20" x="2" y="5"></rect>
<line stroke-width="1.8" x1="2" x2="22" y1="10" y2="10"></line>
</svg>
<span>Semua Rekening</span>
<svg class="w-3.5 h-3.5 text-slate-400 ml-1" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
<!-- Primary Button -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm transition">
<span>+ Catat Transaksi</span>
</button>
</div>
</div>
<!-- BEGIN: 4 Summary KPI Cards -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" data-purpose="kpi-cards">
<!-- Card 1: Total Saldo -->
<div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between">
<div class="flex items-start justify-between">
<span class="text-xs font-medium text-slate-500">Total Saldo</span>
<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<rect height="16" rx="2" stroke-width="1.8" width="20" x="2" y="4"></rect>
<circle cx="16" cy="12" fill="currentColor" r="1.5"></circle>
</svg>
</div>
<div class="mt-3">
<h3 class="text-xl font-bold text-slate-900 tracking-tight">Rp 15.420.000</h3>
<div class="flex items-center gap-1 text-[11px] font-medium text-emerald-600 mt-2">
<span class="font-semibold">↗ +8.4%</span>
<span class="text-slate-400 font-normal">dibanding bulan lalu</span>
</div>
</div>
</div>
<!-- Card 2: Pendapatan -->
<div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between">
<div class="flex items-start justify-between">
<span class="text-xs font-medium text-slate-500">Pendapatan</span>
<svg class="w-4 h-4 text-blue-600 stroke-[2.2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 5L5 19M5 19h10M5 19V9" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div class="mt-3">
<h3 class="text-xl font-bold text-slate-900 tracking-tight">Rp 24.850.000</h3>
<p class="text-[11px] text-slate-400 mt-2">Total pemasukan bulan ini</p>
</div>
</div>
<!-- Card 3: Pengeluaran -->
<div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between">
<div class="flex items-start justify-between">
<span class="text-xs font-medium text-slate-500">Pengeluaran</span>
<svg class="w-4 h-4 text-red-500 stroke-[2.2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M5 19L19 5M19 5H9M19 5v10" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div class="mt-3">
<h3 class="text-xl font-bold text-red-600 tracking-tight">Rp 9.430.000</h3>
<p class="text-[11px] text-slate-400 mt-2">Total pengeluaran bulan ini</p>
</div>
</div>
<!-- Card 4: Laba Bersih -->
<div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm relative flex flex-col justify-between">
<div class="flex items-start justify-between">
<span class="text-xs font-medium text-slate-500">Laba Bersih</span>
<svg class="w-4 h-4 text-blue-600 stroke-[2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M3 17l6-6 4 4 8-8M17 7h4v4" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div class="mt-3">
<h3 class="text-xl font-bold text-slate-900 tracking-tight">Rp 15.420.000</h3>
<p class="text-[11px] text-slate-400 mt-2">Pendapatan – pengeluaran</p>
</div>
</div>
</section>
<!-- END: 4 Summary KPI Cards -->
<!-- BEGIN: Two Column Main Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<!-- Column Left: Grafik Arus Kas & Informasi Rekening (Span 2) -->
<div class="lg:col-span-2 space-y-6">
<!-- Card: Grafik Arus Kas -->
<div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm" data-purpose="cash-flow-chart">
<div class="flex items-center justify-between mb-4">
<div>
<h2 class="text-sm font-bold text-slate-900">Grafik Arus Kas</h2>
<p class="text-[11px] text-slate-400 mt-0.5">Ringkasan mingguan • Oktober 2026</p>
</div>
<!-- Legends -->
<div class="flex items-center gap-4 text-xs font-medium text-slate-500">
<div class="flex items-center gap-1.5">
<span class="w-2.5 h-2.5 rounded-sm bg-blue-600"></span>
<span class="text-[11px]">Pendapatan</span>
</div>
<div class="flex items-center gap-1.5">
<span class="w-2.5 h-2.5 rounded-sm bg-slate-300"></span>
<span class="text-[11px]">Pengeluaran</span>
</div>
</div>
</div>
<!-- Custom Clean SVG/CSS Bar Chart -->
<div class="relative pt-6 pb-2">
<div class="h-44 flex flex-col justify-between text-[11px] text-slate-400 relative">
<!-- Grid Lines -->
<div class="border-b border-slate-100 w-full flex items-center justify-between pb-1">
<span>8 jt</span>
</div>
<div class="border-b border-slate-100 w-full flex items-center justify-between pb-1">
<span>6 jt</span>
</div>
<div class="border-b border-slate-100 w-full flex items-center justify-between pb-1">
<span>4 jt</span>
</div>
<div class="border-b border-slate-100 w-full flex items-center justify-between pb-1">
<span>2 jt</span>
</div>
<div class="border-b border-slate-200 w-full flex items-center justify-between pb-1">
<span>0</span>
</div>
<!-- Bars Overlay -->
<div class="absolute inset-0 left-12 right-6 bottom-0 flex items-end justify-around pb-[1px]">
<!-- Minggu 1 -->
<div class="flex items-end gap-1.5 group relative">
<!-- Blue Bar (5,40 jt) -->
<div class="w-6 bg-blue-600 rounded-t-sm flex flex-col items-center justify-start relative" style="height: 118px;">
<span class="absolute -top-4 text-[10px] font-bold text-blue-600">5,40</span>
</div>
<!-- Gray Bar (2,10 jt) -->
<div class="w-6 bg-slate-300 rounded-t-sm flex flex-col items-center justify-start relative" style="height: 46px;">
<span class="absolute -top-4 text-[10px] font-medium text-slate-500">2,10</span>
</div>
</div>
<!-- Minggu 2 -->
<div class="flex items-end gap-1.5 group relative">
<!-- Blue Bar (6,20 jt) -->
<div class="w-6 bg-blue-600 rounded-t-sm flex flex-col items-center justify-start relative" style="height: 136px;">
<span class="absolute -top-4 text-[10px] font-bold text-blue-600">6,20</span>
</div>
<!-- Gray Bar (2,45 jt) -->
<div class="w-6 bg-slate-300 rounded-t-sm flex flex-col items-center justify-start relative" style="height: 54px;">
<span class="absolute -top-4 text-[10px] font-medium text-slate-500">2,45</span>
</div>
</div>
<!-- Minggu 3 -->
<div class="flex items-end gap-1.5 group relative">
<!-- Blue Bar (5,85 jt) -->
<div class="w-6 bg-blue-600 rounded-t-sm flex flex-col items-center justify-start relative" style="height: 128px;">
<span class="absolute -top-4 text-[10px] font-bold text-blue-600">5,85</span>
</div>
<!-- Gray Bar (2,18 jt) -->
<div class="w-6 bg-slate-300 rounded-t-sm flex flex-col items-center justify-start relative" style="height: 48px;">
<span class="absolute -top-4 text-[10px] font-medium text-slate-500">2,18</span>
</div>
</div>
<!-- Minggu 4 -->
<div class="flex items-end gap-1.5 group relative">
<!-- Blue Bar (7,40 jt) -->
<div class="w-6 bg-blue-600 rounded-t-sm flex flex-col items-center justify-start relative" style="height: 162px;">
<span class="absolute -top-4 text-[10px] font-bold text-blue-600">7,40</span>
</div>
<!-- Gray Bar (2,70 jt) -->
<div class="w-6 bg-slate-300 rounded-t-sm flex flex-col items-center justify-start relative" style="height: 60px;">
<span class="absolute -top-4 text-[10px] font-medium text-slate-500">2,70</span>
</div>
</div>
</div>
</div>
<!-- X-Axis Labels -->
<div class="flex justify-around pl-12 pr-6 mt-3 text-[11px] font-medium text-slate-500">
<span>Minggu 1</span>
<span>Minggu 2</span>
<span>Minggu 3</span>
<span>Minggu 4</span>
</div>
</div>
</div>
<!-- Card: Informasi Rekening -->
<div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm" data-purpose="bank-accounts">
<div class="flex items-center justify-between mb-4">
<h2 class="text-sm font-bold text-slate-900">Informasi Rekening</h2>
<span class="text-[11px] font-medium text-slate-500">3 rekening aktif</span>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
<!-- Kas Outlet -->
<div class="pt-2 sm:pt-0 sm:pr-4">
<div class="flex items-center gap-2 text-slate-600 mb-1.5">
<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<rect height="12" rx="2" stroke-width="1.8" width="20" x="2" y="6"></rect>
<circle cx="12" cy="12" r="2.5" stroke-width="1.8"></circle>
</svg>
<span class="text-xs font-semibold text-slate-700">Kas</span>
</div>
<div class="text-base font-bold text-slate-900">Rp 3.420.000</div>
<p class="text-[11px] text-slate-400 mt-0.5">Tunai di outlet</p>
</div>
<!-- Bank BCA -->
<div class="pt-3 sm:pt-0 sm:px-6">
<div class="flex items-center gap-2 text-slate-600 mb-1.5">
<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 3l9 5H3l9-5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
<span class="text-xs font-semibold text-slate-700">Bank BCA</span>
</div>
<div class="text-base font-bold text-slate-900">Rp 8.500.000</div>
<p class="text-[11px] text-slate-400 mt-0.5">Rekening usaha</p>
</div>
<!-- QRIS -->
<div class="pt-3 sm:pt-0 sm:pl-6">
<div class="flex items-center gap-2 text-slate-600 mb-1.5">
<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<rect height="7" stroke-width="1.8" width="7" x="3" y="3"></rect>
<rect height="7" stroke-width="1.8" width="7" x="14" y="3"></rect>
<rect height="7" stroke-width="1.8" width="7" x="3" y="14"></rect>
<path d="M14 14h3v3h-3zM17 17h4v4h-4zM14 20h3v1h-3zM20 14v3" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
<span class="text-xs font-semibold text-slate-700">QRIS</span>
</div>
<div class="text-base font-bold text-slate-900">Rp 3.500.000</div>
<p class="text-[11px] text-slate-400 mt-0.5">Pembayaran digital</p>
</div>
</div>
</div>
</div>
<!-- Column Right: Riwayat Transaksi Terbaru (Span 1) -->
<div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm flex flex-col justify-between" data-purpose="recent-transactions">
<div>
<div class="mb-4">
<h2 class="text-sm font-bold text-slate-900">Riwayat Transaksi Terbaru</h2>
<div class="flex items-center gap-1.5 mt-1 text-[11px] font-medium text-slate-500">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
<span>Hari ini, 20 Oktober 2026</span>
</div>
</div>
<!-- Transaction List -->
<div class="divide-y divide-slate-100">
<!-- Item 1: POS #ORD-0092 -->
<div class="py-3.5 flex items-center justify-between first:pt-1">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
<svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 5L5 19M5 19h10M5 19V9" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<h4 class="text-xs font-bold text-slate-900 leading-tight">POS #ORD-0092</h4>
<p class="text-[11px] text-slate-400 mt-0.5">Penjualan • Kas</p>
</div>
</div>
<div class="text-right">
<span class="text-[10px] text-slate-400 block mb-0.5">10.42</span>
<span class="text-xs font-bold text-emerald-600">+Rp 45.000</span>
</div>
</div>
<!-- Item 2: Bahan Baku Susu & Kopi -->
<div class="py-3.5 flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0">
<svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M5 19L19 5M19 5H9M19 5v10" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<h4 class="text-xs font-bold text-slate-900 leading-tight">Bahan Baku Susu &amp; Kopi</h4>
<p class="text-[11px] text-slate-400 mt-0.5">Operasional • Bank BCA</p>
</div>
</div>
<div class="text-right">
<span class="text-[10px] text-slate-400 block mb-0.5">09.30</span>
<span class="text-xs font-bold text-red-500">-Rp 450.000</span>
</div>
</div>
<!-- Item 3: Online #ORD-0090 -->
<div class="py-3.5 flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
<svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 5L5 19M5 19h10M5 19V9" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<h4 class="text-xs font-bold text-slate-900 leading-tight">Online #ORD-0090</h4>
<p class="text-[11px] text-slate-400 mt-0.5">Penjualan • QRIS</p>
</div>
</div>
<div class="text-right">
<span class="text-[10px] text-slate-400 block mb-0.5">09.15</span>
<span class="text-xs font-bold text-emerald-600">+Rp 175.000</span>
</div>
</div>
<!-- Item 4: Bayar Listrik -->
<div class="py-3.5 flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0">
<svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M5 19L19 5M19 5H9M19 5v10" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<div>
<h4 class="text-xs font-bold text-slate-900 leading-tight">Bayar Listrik</h4>
<p class="text-[11px] text-slate-400 mt-0.5">Tagihan • Bank BCA</p>
</div>
</div>
<div class="text-right">
<span class="text-[10px] text-slate-400 block mb-0.5">08.00</span>
<span class="text-xs font-bold text-red-500">-Rp 650.000</span>
</div>
</div>
</div>
</div>
<!-- Bottom Link -->
<div class="pt-4 border-t border-slate-100 text-center">
<a class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700" href="#">
<span>Lihat Semua Transaksi</span>
<svg class="w-3.5 h-3.5 stroke-[2.2]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</a>
</div>
</div>
</div>
<!-- END: Two Column Main Layout -->
</main>
@endsection
