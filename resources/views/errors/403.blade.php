<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Dibatasi - UMKMGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xl shadow-slate-200/50 text-center">
        <!-- Shield Warning Icon -->
        <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center border border-amber-200/60 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002A11.959 11.959 0 0112 3.464zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>

        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">403 - Akses Dibatasi</h1>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            Akun Anda saat ini tidak memiliki izin (Permission) untuk mengakses halaman atau fitur ini sesuai aturan <strong>RBAC UMKM GO</strong>.
        </p>

        @if(!empty($requiredPermissions))
            <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-left">
                <span class="font-bold text-slate-700 block mb-1">Izin yang Dibutuhkan:</span>
                <div class="flex flex-wrap gap-1.5 mt-1">
                    @foreach($requiredPermissions as $perm)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono font-medium bg-amber-100 text-amber-800 text-[11px]">
                            {{ $perm }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-6 flex flex-col gap-2.5">
            <a href="{{ route('admin.dashboard') }}" class="w-full py-3 px-4 rounded-xl font-bold text-sm bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Dashboard
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl font-semibold text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                    Login dengan Akun Lain
                </button>
            </form>
        </div>
    </div>
</body>
</html>
