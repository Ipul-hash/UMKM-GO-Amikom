<header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between relative z-30 flex-shrink-0">
    <!-- Left Topbar Brand Icon -->
    <div class="flex items-center gap-3">
        <div class="store-brand-logo-container w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white overflow-hidden shrink-0">
            @if(!empty($currentStore['logo_url']))
                <img src="{{ $currentStore['logo_url'] }}" alt="Logo" class="w-full h-full object-cover">
            @else
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M4 6V4h16v2H4zm1 3h14l-1 12H6L5 9zm4 2v6h2v-6H9zm4 0v6h2v-6h-2z"></path>
                </svg>
            @endif
        </div>
        <span class="font-bold text-lg text-slate-900 tracking-tight">
            UMKM<span class="text-blue-600">Go</span>
        </span>
    </div>

    <!-- Right Topbar Controls -->
    <div class="flex items-center gap-4 sm:gap-6">
        <!-- Store Switcher Dropdown -->
        <button class="flex items-center gap-2 text-slate-700 hover:text-slate-900 font-medium text-sm transition-colors" type="button">
            <div class="store-brand-logo-container w-5 h-5 rounded flex items-center justify-center overflow-hidden shrink-0">
                @if(!empty($currentStore['logo_url']))
                    <img src="{{ $currentStore['logo_url'] }}" alt="Logo" class="w-full h-full object-cover">
                @else
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                @endif
            </div>
            <span class="hidden md:inline store-brand-name">{{ $currentStore['name'] ?? 'Toko Kopi & Fashion Andi' }}</span>
            <svg class="w-4 h-4 text-slate-400 ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6"></path>
            </svg>
        </button>

        <!-- Divider -->
        <div class="w-px h-6 bg-slate-200"></div>

        <!-- Notification Bell with Dot -->
        <button class="relative text-slate-600 hover:text-slate-900 transition-colors p-1" type="button" aria-label="Notifikasi">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
            </svg>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
        </button>

        <!-- Profile / Owner -->
        <div class="flex items-center gap-3 cursor-pointer">
            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-semibold text-xs flex items-center justify-center">
                {{ $currentUser['initials'] ?? 'AB' }}
            </div>
            <div class="text-left hidden sm:block">
                <div class="text-xs font-bold text-slate-900 leading-tight">
                    {{ $currentUser['name'] ?? 'Adam Bagas' }}
                </div>
                <div class="text-[11px] text-slate-400 font-medium leading-tight">
                    {{ $currentUser['role'] ?? 'Owner' }}
                </div>
            </div>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6"></path>
            </svg>
        </div>
    </div>
</header>
