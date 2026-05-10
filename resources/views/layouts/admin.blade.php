<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Q-Les HQ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-white font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-60 bg-[#1e2a3a] flex-shrink-0 flex flex-col h-screen overflow-y-auto">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <i class="fas fa-shield-alt text-blue-400 text-xl"></i>
            <span class="text-white font-bold text-lg tracking-wide">Q-LES HQ</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-4 space-y-1">
            <x-admin.sidebar-item
                route="admin.dashboard"
                icon="fas fa-th-large"
                label="Dashboard"
            />
            <x-admin.sidebar-item
                route="admin.users"
                icon="fas fa-users"
                label="Pengguna"
            />
            <x-admin.sidebar-item
                route="admin.logs"
                icon="fas fa-file-invoice"
                label="Pesanan"
            />
            <x-admin.sidebar-item
                route="admin.logs"
                icon="fas fa-chart-bar"
                label="Laporan"
            />
            <x-admin.sidebar-item
                route="admin.dashboard"
                icon="fas fa-cog"
                label="Pengaturan"
            />

            <div class="pt-4 border-t border-white/10 mt-4 space-y-1">
                <x-admin.sidebar-item
                    route="admin.service"
                    icon="fas fa-headset"
                    label="Pesan CS"
                />
                <x-admin.sidebar-item
                    route="admin.ratings"
                    icon="fas fa-star"
                    label="Rating & Ulasan"
                />
                <x-admin.sidebar-item
                    route="admin.dashboard"
                    icon="fas fa-bell"
                    label="Notifikasi"
                />
                <x-admin.sidebar-item
                    route="admin.dashboard"
                    icon="fas fa-question-circle"
                    label="Bantuan"
                />
            </div>
        </nav>

        {{-- Logout --}}
        <div class="px-4 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-4 py-2.5 rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors duration-200 text-sm font-medium">
                    <i class="fas fa-sign-out-alt w-4 text-center"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 flex-shrink-0">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="flex items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=3b82f6&color=fff&size=40"
                        class="w-9 h-9 rounded-full border-2 border-blue-200"
                        alt="{{ auth()->user()->name ?? 'Admin' }}">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-blue-600 font-medium">Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>

    </div>
</div>

@stack('scripts')
</body>
</html>
