<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Q-Les HQ</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        
        /* Custom Scrollbar for better UI */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-right: 1px solid rgba(255, 255, 255, 0.9);
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-[#f0f9ff] text-slate-800 antialiased selection:bg-blue-500/20 relative">

<!-- Background subtle decorations -->
<div class="fixed top-[-10%] left-[-5%] w-[400px] h-[400px] bg-sky-200/40 rounded-full blur-[100px] pointer-events-none z-0"></div>
<div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[500px] bg-blue-200/40 rounded-full blur-[120px] pointer-events-none z-0"></div>

<div class="flex h-screen overflow-hidden relative z-10">

    {{-- Sidebar --}}
    <aside class="w-[260px] glass-sidebar shadow-[4px_0_24px_rgba(14,165,233,0.05)] flex-shrink-0 flex flex-col h-screen overflow-y-auto">
        {{-- Logo --}}
        <div class="flex items-center gap-4 px-6 py-6 border-b border-slate-100/60">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-sky-400 flex items-center justify-center shadow-md shadow-blue-500/20">
                <i class="fas fa-shield-alt text-white text-lg"></i>
            </div>
            <span class="text-slate-800 font-black text-xl tracking-tight">Q-LES<span class="text-blue-500">.HQ</span></span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1.5">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-3">Menu Utama</div>
            <x-admin.sidebar-item route="admin.dashboard" icon="fas fa-th-large" label="Dashboard" />
            <x-admin.sidebar-item route="admin.users" icon="fas fa-users" label="Pengguna" />
            <x-admin.sidebar-item route="admin.classes" icon="fas fa-chalkboard" label="Kelas" />
            <x-admin.sidebar-item route="admin.logs" icon="fas fa-file-invoice" label="Pesanan" />
            <x-admin.sidebar-item route="admin.logs" icon="fas fa-chart-bar" label="Laporan" />
            <x-admin.sidebar-item route="admin.settings" icon="fas fa-cog" label="Pengaturan" />

            <div class="pt-6 mt-6 border-t border-slate-100/60 space-y-1.5">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-3">Dukungan</div>
                <x-admin.sidebar-item route="admin.service" icon="fas fa-headset" label="Pesan CS" />
                <x-admin.sidebar-item route="admin.ratings" icon="fas fa-star" label="Rating & Ulasan" />
                <x-admin.sidebar-item route="admin.dashboard" icon="fas fa-bell" label="Notifikasi" />
                <x-admin.sidebar-item route="admin.dashboard" icon="fas fa-question-circle" label="Bantuan" />
            </div>
        </nav>

        {{-- Logout --}}
        <div class="px-4 py-5 border-t border-slate-100/60">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-rose-500 font-bold hover:bg-rose-50 hover:text-rose-600 transition-all duration-300">
                    <i class="fas fa-sign-out-alt w-5 text-center text-lg"></i>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        <header class="glass-header shadow-sm h-20 flex items-center justify-between px-8 flex-shrink-0">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4">
                <button class="relative w-10 h-10 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-xl transition-all duration-300">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
                </button>
                <div class="h-8 w-px bg-slate-200 mx-1"></div>
                <div class="flex items-center gap-3 cursor-pointer p-1.5 pr-3 rounded-full hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=e0f2fe&color=0ea5e9&bold=true&size=40"
                        class="w-10 h-10 rounded-full border border-sky-200 shadow-sm"
                        alt="{{ auth()->user()->name ?? 'Admin' }}">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800 leading-none mb-1">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[11px] font-bold text-blue-500 uppercase tracking-wider leading-none">Super Admin</p>
                    </div>
                    <i class="fas fa-chevron-down text-slate-400 text-xs ml-1"></i>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-8 relative">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>
</div>

@stack('scripts')
</body>
</html>
