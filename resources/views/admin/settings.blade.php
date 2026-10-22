@extends('layouts.admin')

@section('title', 'UMKMGo - Pengaturan Toko')

@section('content')
<!-- END: TopBar -->
<!-- BEGIN: MainContent -->
<main class="flex-1 overflow-y-auto px-8 py-7" data-purpose="main-layout">
<!-- Title & Subtitle Area -->
<div class="mb-7">
<h1 class="text-2xl font-bold tracking-tight text-slate-900">Pengaturan Toko</h1>
<p class="text-[13px] text-slate-500 mt-1">Kelola profil bisnis dan preferensi aplikasi</p>
</div>
<!-- Two-Column Settings Container Layout -->
<div class="grid grid-cols-12 gap-6 items-start">
<!-- BEGIN: SettingsSubNav -->
<div class="col-span-12 lg:col-span-3">
<div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-sm" data-purpose="settings-sub-navigation">
<div class="px-2 pb-2.5">
<span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">PENGATURAN TOKO</span>
</div>
<div class="space-y-1">
<!-- Profil Toko (Active Item) -->
<a class="flex items-center justify-between px-3 py-2.5 bg-blue-50 text-brand-blue rounded-xl font-medium text-xs transition-colors" href="#">
<div class="flex items-center gap-2.5">
<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
<polyline points="9 22 9 12 15 12 15 22"></polyline>
</svg>
<span>Profil Toko</span>
</div>
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewbox="0 0 24 24">
<polyline points="9 18 15 12 9 6"></polyline>
</svg>
</a>
<!-- Pajak & Biaya -->
<a class="flex items-center gap-2.5 px-3 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-xl font-medium text-xs transition-colors" href="#">
<svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<line x1="19" x2="5" y1="5" y2="19"></line>
<circle cx="6.5" cy="6.5" r="2.5"></circle>
<circle cx="17.5" cy="17.5" r="2.5"></circle>
</svg>
<span>Pajak &amp; Biaya</span>
</a>
<!-- Struk Kasir -->
<a class="flex items-center gap-2.5 px-3 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-xl font-medium text-xs transition-colors" href="#">
<svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
<polyline points="14 2 14 8 20 8"></polyline>
<line x1="16" x2="8" y1="13" y2="13"></line>
<line x1="16" x2="8" y1="17" y2="17"></line>
<polyline points="10 9 9 9 8 9"></polyline>
</svg>
<span>Struk Kasir</span>
</a>
<!-- Printer POS -->
<a class="flex items-center gap-2.5 px-3 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-xl font-medium text-xs transition-colors" href="#">
<svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<polyline points="6 9 6 2 18 2 18 9"></polyline>
<path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
<rect height="8" width="12" x="6" y="14"></rect>
</svg>
<span>Printer POS</span>
</a>
</div>
</div>
</div>
<!-- END: SettingsSubNav -->
<!-- BEGIN: SettingsFormCard -->
<div class="col-span-12 lg:col-span-9">
<div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm flex flex-col" data-purpose="settings-form-panel">
<div class="p-7">
<!-- Form Section Header -->
<div class="mb-6">
<h2 class="text-base font-semibold text-slate-900">Profil Toko</h2>
<p class="text-xs text-slate-400 mt-0.5">Perbarui identitas dan informasi kontak toko Anda.</p>
</div>
<!-- Store Logo Uploader Section -->
<div class="flex items-center gap-4 mb-7" data-purpose="store-logo-section">
<!-- Image Placeholder Container -->
<div class="w-20 h-20 rounded-xl border border-dashed border-slate-200 bg-slate-50 flex items-center justify-center flex-shrink-0 text-slate-400">
<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewbox="0 0 24 24">
<rect height="18" rx="2" ry="2" width="18" x="3" y="3"></rect>
<circle cx="8.5" cy="8.5" r="1.5"></circle>
<polyline points="21 15 16 10 5 21"></polyline>
<line x1="16" x2="16" y1="5" y2="9"></line>
<line x1="14" x2="18" y1="7" y2="7"></line>
</svg>
</div>
<!-- Actions -->
<div class="flex flex-col gap-1.5">
<button class="inline-flex items-center justify-center gap-2 px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm w-fit" type="button">
<svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
<polyline points="17 8 12 3 7 8"></polyline>
<line x1="12" x2="12" y1="3" y2="15"></line>
</svg>
<span>Upload Logo Toko</span>
</button>
<span class="text-[11px] text-slate-400">JPG atau PNG. Maksimal 2 MB.</span>
</div>
</div>
<!-- Form Fields Grid -->
<form class="space-y-4">
<!-- Grid Row 1: Nama Toko & Nama Pemilik -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Toko</label>
<input class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 shadow-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue placeholder:text-slate-400" type="text" value="Toko Kopi &amp; Fashion Andi"/>
</div>
<div>
<label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Pemilik</label>
<input class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 shadow-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue placeholder:text-slate-400" type="text" value="Adam Bagas"/>
</div>
</div>
<!-- Grid Row 2: No. WhatsApp & Email -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<label class="block text-xs font-medium text-slate-700 mb-1.5">No. WhatsApp</label>
<input class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 shadow-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue placeholder:text-slate-400" type="text" value="0812-8888-9999"/>
</div>
<div>
<label class="block text-xs font-medium text-slate-700 mb-1.5">Email</label>
<input class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 shadow-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue placeholder:text-slate-400" type="email" value="admin@umkmgo.id"/>
</div>
</div>
<!-- Grid Row 3: Alamat -->
<div>
<label class="block text-xs font-medium text-slate-700 mb-1.5">Alamat</label>
<textarea class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 shadow-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue placeholder:text-slate-400 resize-none" rows="3">Jl. Merdeka No. 12, Purwokerto</textarea>
</div>
</form>
</div>
<!-- Form Action Footer -->
<div class="px-7 py-4 border-t border-slate-100 flex justify-end items-center">
<button class="inline-flex items-center gap-2 bg-brand-blue hover:bg-blue-700 text-white font-medium text-xs px-4 py-2.5 rounded-lg shadow-sm transition" type="submit">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
<polyline points="17 21 17 13 7 13 7 21"></polyline>
<polyline points="7 3 7 8 15 8"></polyline>
</svg>
<span>Simpan Perubahan</span>
</button>
</div>
</div>
</div>
<!-- END: SettingsFormCard -->
</div>
</main>
<!-- END: MainContent -->
</div>
<!-- END: Main Container -->
</body></html>
@endsection
