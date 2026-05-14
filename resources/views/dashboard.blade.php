<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Banner -->
            <div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 rounded-3xl p-10 shadow-2xl overflow-hidden group">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-64 h-64 rounded-full bg-white opacity-10 blur-3xl group-hover:opacity-20 transition duration-700"></div>
                <div class="absolute bottom-0 right-1/4 -mb-10 w-40 h-40 rounded-full bg-blue-400 opacity-20 blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div class="text-white mb-6 md:mb-0">
                        <span class="inline-block py-1 px-3 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-sm font-semibold mb-4 tracking-wider uppercase shadow-inner">Administrator Portal</span>
                        <h3 class="text-4xl font-black tracking-tight mb-3">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
                        <p class="text-indigo-100 text-lg max-w-2xl font-medium leading-relaxed">Kelola sistem edukasi Q-Les dengan mudah. Pantau aktivitas pengguna, atur layanan pelanggan, dan lihat performa aplikasi hari ini.</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-lg">
                            <div class="text-indigo-100 text-sm font-medium mb-1">Status Sistem</div>
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                <span class="text-white font-bold">Online & Normal</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role == 'super_admin')
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6                    <!-- Stat 1 -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Total Pengguna</p>
                            <h4 class="text-2xl font-bold text-slate-800">{{ \App\Models\User::count() }}</h4>
                        </div>
                    </div>
                    <!-- Stat 2 -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="p-3 rounded-xl bg-purple-50 text-purple-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Kelas Aktif</p>
                            <h4 class="text-2xl font-bold text-slate-800">{{ class_exists('\App\Models\Classes') ? \App\Models\Classes::count() : 0 }}</h4>
                        </div>
                    </div>
                    <!-- Stat 3 -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Tiket Terbuka</p>
                            <h4 class="text-2xl font-bold text-slate-800">{{ class_exists('\App\Models\SupportTicket') ? \App\Models\SupportTicket::where('status', 'open')->count() : 0 }}</h4>
                        </div>
                    </div>
                    <!-- Stat 4 -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="p-3 rounded-xl bg-rose-50 text-rose-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Total Pesanan</p>
                            <h4 class="text-2xl font-bold text-slate-800">{{ class_exists('\App\Models\Submission') ? \App\Models\Submission::count() : 0 }}</h4>
                        </div>
                    </div>iv>
                </div>

                <div class="mt-4 mb-2 flex items-center">
                    <h3 class="text-xl font-bold text-slate-800">Modul Utama</h3>
                    <div class="h-px bg-slate-200 flex-1 ml-4"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- HQ Card -->
                    <div class="group relative bg-white rounded-3xl p-8 shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-rose-100 to-transparent rounded-bl-full opacity-50"></div>
                        <div class="flex items-start gap-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-rose-400 to-red-600 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-rose-200 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-2xl font-bold text-slate-800 mb-2 group-hover:text-rose-600 transition-colors">Pusat Komando (HQ)</h4>
                                <p class="text-slate-500 mb-6 leading-relaxed">Akses panel kontrol utama untuk manajemen pengguna, pemantauan kelas, serta analisis data statistik secara mendalam.</p>
                                <a href="{{ url('/hq-admin/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 text-white font-semibold rounded-xl hover:bg-rose-600 transition-colors shadow-md group-hover:shadow-rose-200">
                                    Masuk ke Panel HQ
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Service Center Card -->
                    <div class="group relative bg-white rounded-3xl p-8 shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-indigo-100 to-transparent rounded-bl-full opacity-50"></div>
                        <div class="flex items-start gap-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-indigo-200 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-2xl font-bold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">Service Center</h4>
                                <p class="text-slate-500 mb-6 leading-relaxed">Berkomunikasi langsung dengan pengguna, kelola tiket laporan, dan selesaikan masalah teknis aplikasi secara real-time.</p>
                                <a href="{{ url('/hq-admin/service-center') }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 text-white font-semibold rounded-xl hover:bg-indigo-600 transition-colors shadow-md group-hover:shadow-indigo-200">
                                    Buka Tiket CS
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Normal User Dashboard -->
                <div class="bg-white rounded-2xl p-10 shadow-sm border border-slate-100 mt-8 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Akses Terbatas</h3>
                    <p class="text-slate-500 max-w-md mx-auto">Anda login sebagai pengguna biasa. Silakan gunakan aplikasi seluler Q-Les di smartphone Anda untuk mendapatkan fitur yang lebih lengkap.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>