@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

{{-- Search Bar --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
    <form method="GET" action="{{ route('admin.users') }}" class="flex gap-3">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari berdasarkan nama atau email..."
                class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
        </div>
        <button type="submit"
            class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            Cari
        </button>
        @if($search)
        <a href="{{ route('admin.users') }}"
            class="px-4 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- Local Users Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-gray-800">Pengguna Lokal</h3>
        <span class="text-xs text-gray-500">{{ $users->total() }} pengguna ditemukan</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Role</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Daftar</th>
                    <th class="text-right py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-3">
                        <div class="flex items-center gap-2.5">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=3b82f6&color=fff&size=32"
                                class="w-8 h-8 rounded-full flex-shrink-0"
                                alt="{{ $user->name }}">
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-3 text-gray-600">{{ $user->email }}</td>
                    <td class="py-3 px-3">
                        @php
                            $roleColor = match($user->role) {
                                'super_admin' => 'bg-purple-100 text-purple-700',
                                'teacher'     => 'bg-blue-100 text-blue-700',
                                'student'     => 'bg-green-100 text-green-700',
                                default       => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $roleColor }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td class="py-3 px-3 text-gray-500 text-xs">
                        {{ $user->created_at?->format('d M Y') ?? '-' }}
                    </td>
                    <td class="py-3 px-3 text-right">
                        @if($user->id !== auth()->id())
                        <form
                            method="POST"
                            action="{{ route('admin.users.destroy', $user->id) }}"
                            onsubmit="return confirm('Hapus pengguna {{ addslashes($user->name) }} secara permanen?')"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                <i class="fas fa-trash text-xs"></i>
                                Hapus
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400 italic">Akun Anda</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-400 text-sm">
                        @if($search)
                            Tidak ada pengguna yang cocok dengan pencarian "{{ $search }}".
                        @else
                            Belum ada pengguna terdaftar.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="mt-4 pt-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Firebase Users Section --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 flex items-center justify-center">
                <svg viewBox="0 0 32 32" class="w-5 h-5" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.5 25.5L11.5 6.5L18.5 19.5L21.5 14.5L27.5 25.5H5.5Z" fill="#FFA000"/>
                    <path d="M5.5 25.5L11.5 6.5L18.5 19.5L5.5 25.5Z" fill="#F57F17"/>
                    <path d="M18.5 19.5L21.5 14.5L27.5 25.5H18.5V19.5Z" fill="#FFCA28"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-800">Akun Firebase Authentication</h3>
        </div>
        @if(isset($firebaseUsers['data']) && count($firebaseUsers['data']) > 0)
        @php
            $googleCount = collect($firebaseUsers['data'])->where('loginGoogle', true)->count();
        @endphp
        <div class="flex items-center gap-3 text-xs text-gray-500">
            <span class="flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
                {{ $googleCount }} login Google
            </span>
            <span class="flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span>
                {{ count($firebaseUsers['data']) - $googleCount }} lainnya
            </span>
            <span class="font-medium text-gray-700">Total: {{ count($firebaseUsers['data']) }}</span>
        </div>
        @endif
    </div>

    @if(isset($firebaseUsers['error']) && $firebaseUsers['error'])
    <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
        <i class="fas fa-exclamation-triangle mt-0.5 flex-shrink-0"></i>
        <span>⚠️ {{ $firebaseUsers['error'] }}</span>
    </div>
    @elseif(isset($firebaseUsers['data']) && count($firebaseUsers['data']) > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Pengguna</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Provider</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Terakhir Login</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($firebaseUsers['data'] as $fbUser)
                <tr class="hover:bg-gray-50 transition-colors {{ $fbUser['disabled'] ? 'opacity-50' : '' }}">
                    {{-- Kolom Pengguna --}}
                    <td class="py-3 px-3">
                        <div class="flex items-center gap-2.5">
                            @if($fbUser['photoUrl'])
                            <img src="{{ $fbUser['photoUrl'] }}"
                                class="w-8 h-8 rounded-full flex-shrink-0 border border-gray-200"
                                alt="{{ $fbUser['displayName'] ?? 'User' }}"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($fbUser['displayName'] ?? $fbUser['email']) }}&background=e5e7eb&color=6b7280&size=32'">
                            @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($fbUser['displayName'] ?? $fbUser['email']) }}&background=e5e7eb&color=6b7280&size=32"
                                class="w-8 h-8 rounded-full flex-shrink-0"
                                alt="{{ $fbUser['displayName'] ?? 'User' }}">
                            @endif
                            <div>
                                <p class="font-medium text-gray-800 text-xs leading-tight">
                                    {{ $fbUser['displayName'] ?? '(Tanpa Nama)' }}
                                </p>
                                <p class="font-mono text-gray-400 text-xs leading-tight truncate max-w-[120px]" title="{{ $fbUser['uid'] }}">
                                    {{ $fbUser['uid'] }}
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- Kolom Email --}}
                    <td class="py-3 px-3 text-gray-700 text-xs">{{ $fbUser['email'] }}</td>

                    {{-- Kolom Provider --}}
                    <td class="py-3 px-3">
                        @if($fbUser['loginGoogle'])
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            {{-- Google Icon --}}
                            <svg class="w-3 h-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Google
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            <i class="fas fa-envelope text-xs"></i>
                            Email/Password
                        </span>
                        @endif
                    </td>

                    {{-- Kolom Terakhir Login --}}
                    <td class="py-3 px-3 text-gray-500 text-xs">
                        @if($fbUser['lastLoginAt'])
                        @php
                            // lastLoginAt dari Firebase adalah object DateTimeImmutable
                            $lastLogin = \Carbon\Carbon::instance($fbUser['lastLoginAt']);
                        @endphp
                        <span title="{{ $lastLogin->format('d M Y H:i:s') }}">
                            {{ $lastLogin->diffForHumans() }}
                        </span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>

                    {{-- Kolom Status --}}
                    <td class="py-3 px-3">
                        @if($fbUser['disabled'])
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                            <i class="fas fa-ban text-xs"></i> Dinonaktifkan
                        </span>
                        @elseif($fbUser['emailVerified'])
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            <i class="fas fa-check-circle text-xs"></i> Terverifikasi
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                            <i class="fas fa-clock text-xs"></i> Belum Verifikasi
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-sm text-gray-400 text-center py-6">
        Tidak ada akun Firebase yang ditemukan atau konfigurasi belum diatur.
    </p>
    @endif
</div>

@endsection
