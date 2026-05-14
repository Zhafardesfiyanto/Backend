@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan')

@section('content')

{{-- Header --}}
<div class="mb-8">
    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Sistem</h1>
    <p class="text-slate-500 mt-1 text-sm font-medium">Konfigurasi aplikasi, manajemen token, dan pengaturan notifikasi platform.</p>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
        <i class="fas fa-check-circle"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Sidebar Navigation --}}
        <div class="lg:col-span-1 space-y-2">
            <button type="button" class="w-full text-left px-5 py-3 rounded-xl font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-sm transition-colors flex items-center justify-between">
                <span class="flex items-center gap-3"><i class="fas fa-sliders-h w-5"></i>Umum</span>
                <i class="fas fa-chevron-right text-xs"></i>
            </button>
            <button type="button" class="w-full text-left px-5 py-3 rounded-xl font-semibold text-slate-500 hover:bg-white hover:text-slate-800 border border-transparent transition-colors flex items-center gap-3">
                <i class="fas fa-database w-5"></i> Integrasi Data
            </button>
            <button type="button" class="w-full text-left px-5 py-3 rounded-xl font-semibold text-slate-500 hover:bg-white hover:text-slate-800 border border-transparent transition-colors flex items-center gap-3">
                <i class="fas fa-shield-alt w-5"></i> Keamanan
            </button>
            <button type="button" class="w-full text-left px-5 py-3 rounded-xl font-semibold text-slate-500 hover:bg-white hover:text-slate-800 border border-transparent transition-colors flex items-center gap-3">
                <i class="fas fa-bell w-5"></i> Notifikasi
            </button>
        </div>

        {{-- Main Settings Form --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Card: App Settings --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <div class="mb-6 pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-bold text-slate-800">Profil Aplikasi</h3>
                    <p class="text-sm text-slate-500 mt-1">Sesuaikan informasi dasar aplikasi yang ditampilkan ke pengguna.</p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nama Aplikasi</label>
                        <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'Q-Les' }}" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50/50" placeholder="Masukkan nama aplikasi">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Email Dukungan Layanan (CS)</label>
                        <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'support@q-les.com' }}" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50/50" placeholder="Email untuk Customer Service">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Zona Waktu</label>
                        <select name="timezone" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50/50 cursor-pointer">
                            <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? '') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                            <option value="Asia/Makassar" {{ ($settings['timezone'] ?? '') == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                            <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? '') == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Card: System Preferences --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <div class="mb-6 pb-4 border-b border-slate-100">
                    <h3 class="text-xl font-bold text-slate-800">Preferensi Sistem</h3>
                    <p class="text-sm text-slate-500 mt-1">Atur perilaku sistem dan pendaftaran pengguna baru.</p>
                </div>

                <div class="space-y-6">
                    <!-- Toggle 1 -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">Registrasi Pengguna Terbuka</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Izinkan pengguna mendaftar melalui aplikasi mobile.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="registration_open" value="1" class="sr-only peer" {{ ($settings['registration_open'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    
                    <!-- Toggle 2 -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">Mode Perawatan (Maintenance)</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Nonaktifkan akses API sementara waktu untuk perbaikan.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-rose-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                        </label>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-200 transition-all active:scale-95 flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Simpan Seluruh Perubahan
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

@endsection
