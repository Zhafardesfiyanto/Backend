<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 shadow-xl text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                <h3 class="text-3xl font-extrabold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
                <p class="text-indigo-100 text-lg max-w-2xl">Anda berhasil masuk ke sistem Q-Les. Kelola aplikasi, cek tiket bantuan, dan atur pengguna dengan mudah melalui panel kontrol ini.</p>
            </div>

            @if(auth()->user()->role == 'super_admin')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <!-- HQ Card -->
                    <div class="bg-white/80 backdrop-blur-lg border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center text-xl mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Pusat Komando (HQ)</h4>
                        <p class="text-gray-500 mb-6 text-sm">Akses penuh ke ringkasan sistem, manajemen pengguna, dan analisis data aplikasi.</p>
                        <a href="{{ url('/hq-admin/dashboard') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">
                            Masuk ke HQ
                        </a>
                    </div>

                    <!-- Service Center Card -->
                    <div class="bg-white/80 backdrop-blur-lg border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-xl mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Service Center</h4>
                        <p class="text-gray-500 mb-6 text-sm">Balas tiket keluhan dari pelanggan dan pantau masalah teknis aplikasi secara real-time.</p>
                        <a href="{{ url('/hq-admin/service-center') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors">
                            Buka Tiket CS
                        </a>
                    </div>
                </div>
            @else
                <!-- Normal User Dashboard -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mt-8">
                    <p class="text-gray-600 text-center py-8">Anda login sebagai pengguna biasa. Gunakan aplikasi seluler Q-Les untuk fitur lengkap.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>