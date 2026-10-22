<header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between relative z-30 flex-shrink-0">
    <!-- Left Topbar Brand Icon -->
    <div class="flex items-center gap-3">
        @if(!empty($currentStore['logo_url']))
            <div class="store-brand-logo-container flex items-center justify-center shrink-0 h-8 max-w-[120px]">
                <img src="{{ $currentStore['logo_url'] }}" alt="Logo" class="h-8 w-auto max-w-full object-contain">
            </div>
        @else
            <div class="store-brand-logo-container w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white overflow-hidden shrink-0">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M4 6V4h16v2H4zm1 3h14l-1 12H6L5 9zm4 2v6h2v-6H9zm4 0v6h2v-6h-2z"></path>
                </svg>
            </div>
        @endif
        <span class="font-bold text-lg text-slate-900 tracking-tight">
            UMKM<span class="text-blue-600">Go</span>
        </span>
    </div>

    <!-- Right Topbar Controls -->
    <div class="flex items-center gap-4 sm:gap-6">
        <!-- Store Switcher Dropdown -->
        <button class="flex items-center gap-2 text-slate-700 hover:text-slate-900 font-medium text-sm transition-colors" type="button">
            <div class="store-brand-logo-switcher flex items-center justify-center shrink-0">
                @if(!empty($currentStore['logo_url']))
                    <img src="{{ $currentStore['logo_url'] }}" alt="Logo" class="h-5 w-auto max-w-[28px] object-contain">
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

        <!-- Profile / Active User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.outside="open = false" type="button" class="flex items-center gap-3 cursor-pointer p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center border border-blue-200">
                    {{ $currentUser['initials'] ?? 'AB' }}
                </div>
                <div class="text-left hidden sm:block">
                    <div class="text-xs font-bold text-slate-900 leading-tight">
                        {{ $currentUser['name'] ?? 'Adam Bagas' }}
                    </div>
                    <div class="text-[11px] text-blue-600 font-semibold leading-tight">
                        {{ $currentUser['role'] ?? 'Owner' }}
                    </div>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="m6 9 6 6 6-6"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200/80 p-2 z-50">
                <div class="px-3 py-2 border-b border-slate-100">
                    <p class="text-xs font-bold text-slate-800">{{ $currentUser['name'] ?? 'Pengguna' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ $currentUser['email'] ?? 'admin@umkmgo.id' }}</p>
                    <p class="mt-1.5 text-xs font-semibold text-blue-600">{{ $currentUser['role'] ?? 'Owner' }}</p>
                </div>

                <div class="py-1">
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Pengaturan Toko</span>
                    </a>
                </div>

                <div class="pt-1 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Keluar / Ganti Akun</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
