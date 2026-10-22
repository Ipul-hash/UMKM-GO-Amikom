<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UMKMGo - Dashboard')</title>

    <!-- Tailwind CSS CDN with forms & container queries plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

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

    @stack('scripts')
</body>
</html>
