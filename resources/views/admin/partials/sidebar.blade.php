<aside class="w-64 bg-[#0D1527] text-slate-400 flex flex-col flex-shrink-0 min-h-screen select-none border-r border-slate-800/40">
    <!-- Brand Logo Area -->
    <div class="h-20 flex items-center px-6 gap-3">
        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-600/30">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                <path d="M4 6V4h16v2H4zm1 3h14l-1 12H6L5 9zm4 2v6h2v-6H9zm4 0v6h2v-6h-2z"></path>
            </svg>
        </div>
        <div class="flex items-center text-white font-extrabold text-xl tracking-tight">
            UMKM<span class="text-blue-500">Go</span>
        </div>
    </div>

    <!-- Navigation Menu Items -->
    <div class="px-4 py-2 text-[11px] font-bold tracking-wider text-slate-500 uppercase">
        Menu Utama
    </div>

    <nav class="flex-1 px-3 space-y-1 overflow-y-auto">
        <!-- Active Item: Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold shadow-sm transition-all">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect height="7" rx="1.5" width="7" x="3" y="3"></rect>
                    <rect height="7" rx="1.5" width="7" x="14" y="3"></rect>
                    <rect height="7" rx="1.5" width="7" x="14" y="14"></rect>
                    <rect height="7" rx="1.5" width="7" x="3" y="14"></rect>
                </svg>
                <span class="text-sm">Dashboard</span>
            </div>
        </a>

        <!-- Item: POS / Kasir -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect height="16" rx="2" width="20" x="2" y="4"></rect>
                <path d="M7 15h10M2 9.5h20"></path>
            </svg>
            <span>POS / Kasir</span>
        </a>

        <!-- Item: Produk -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                <path d="m3.3 7 8.7 5 8.7-5M12 22V12"></path>
            </svg>
            <span>Produk</span>
        </a>

        <!-- Item: Inventory -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M3 21h18M3 7v1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7H3l2-4h14l2 4M5 21V10.85M19 21V10.85"></path>
            </svg>
            <span>Inventory</span>
        </a>

        <!-- Item: Pesanan Online -->
        <a href="#" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                    <path d="M3 6h18M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <span>Pesanan Online</span>
            </div>
            <span class="px-2 py-0.5 text-xs font-semibold bg-slate-800 text-slate-300 rounded-md">
                {{ $newOrders['total_count'] ?? 3 }}
            </span>
        </a>

        <!-- Item: Keuangan -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect height="14" rx="2" width="20" x="2" y="5"></rect>
                <line x1="2" x2="22" y1="10" y2="10"></line>
            </svg>
            <span>Keuangan</span>
        </a>

        <!-- Item: Pelanggan -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Pelanggan</span>
        </a>

        <!-- Item: Promo -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="m9 15 6-6M9.5 9.5h.01M14.5 14.5h.01"></path>
            </svg>
            <span>Promo</span>
        </a>

        <!-- Item: Laporan -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M3 3v18h18M7 16l4-4 4 4 5-6"></path>
            </svg>
            <span>Laporan</span>
        </a>

        <!-- Item: Karyawan -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Karyawan</span>
        </a>

        <!-- Item: Supplier -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect height="13" rx="2" width="16" x="1" y="6"></rect>
                <path d="M16 8h4a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-1M5 19a2 2 0 1 0 4 0 2 2 0 0 0-4 0Zm10 0a2 2 0 1 0 4 0 2 2 0 0 0-4 0Z"></path>
            </svg>
            <span>Supplier</span>
        </a>

        <!-- Item: Pengaturan -->
        <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 text-sm font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <span>Pengaturan</span>
        </a>
    </nav>
</aside>
