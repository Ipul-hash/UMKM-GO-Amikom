<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UMKMGo - Dashboard')</title>

    <!-- Tailwind CSS CDN with forms & container queries plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            blue: '#2563EB',
                            'blue-light': '#EFF6FF',
                            dark: '#0F172A',
                            sidebar: '#0B132B',
                            'sidebar-hover': '#1C2541'
                        }
                    }
                }
            }
        }
    </script>

    <style data-purpose="base-styling">
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        /* Custom subtle scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-slate-800 flex min-h-screen antialiased">

    <!-- BEGIN: Sidebar -->
    @include('admin.partials.sidebar')
    <!-- END: Sidebar -->

    <!-- BEGIN: Main Viewport Wrapper -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- BEGIN: Topbar -->
        @include('admin.partials.topbar')
        <!-- END: Topbar -->

        <!-- Content Slot -->
        @yield('content')

    </div>
    <!-- END: Main Viewport Wrapper -->

    <!-- Global Toast Notifications -->
    <div x-data="toastHandler()" 
         @show-toast.window="addToast($event.detail)" 
         class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm pointer-events-none">
        <template x-for="item in toasts" :key="item.id">
            <div x-show="item.visible" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 :class="{
                     'bg-emerald-600 text-white': item.type === 'success',
                     'bg-rose-600 text-white': item.type === 'error',
                     'bg-amber-500 text-white': item.type === 'warning',
                     'bg-slate-800 text-white': item.type === 'info'
                 }"
                 class="pointer-events-auto flex items-center justify-between gap-3 px-4 py-3 rounded-xl shadow-xl text-sm font-medium">
                <div class="flex items-center gap-2.5">
                    <template x-if="item.type === 'success'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </template>
                    <template x-if="item.type === 'error'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </template>
                    <template x-if="item.type === 'warning'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </template>
                    <span x-text="item.message"></span>
                </div>
                <button @click="removeToast(item.id)" class="text-white/80 hover:text-white ml-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>

    <script>
        function toastHandler() {
            return {
                toasts: [],
                addToast(detail) {
                    const id = Date.now() + Math.random();
                    const toast = {
                        id: id,
                        type: detail.type || 'info',
                        message: detail.message || '',
                        visible: true
                    };
                    this.toasts.push(toast);
                    setTimeout(() => {
                        this.removeToast(id);
                    }, detail.duration || 3500);
                },
                removeToast(id) {
                    const idx = this.toasts.findIndex(t => t.id === id);
                    if (idx > -1) {
                        this.toasts[idx].visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 200);
                    }
                }
            };
        }

        window.showToast = function(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message, type } }));
        };
    </script>

    @stack('scripts')
</body>
</html>
