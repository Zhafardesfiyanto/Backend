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
    <div class="flex items-center gap-2 mb-4">
        <div class="w-6 h-6 flex items-center justify-center">
            <svg viewBox="0 0 32 32" class="w-5 h-5" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.5 25.5L11.5 6.5L18.5 19.5L21.5 14.5L27.5 25.5H5.5Z" fill="#FFA000"/>
                <path d="M5.5 25.5L11.5 6.5L18.5 19.5L5.5 25.5Z" fill="#F57F17"/>
                <path d="M18.5 19.5L21.5 14.5L27.5 25.5H18.5V19.5Z" fill="#FFCA28"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-gray-800">Akun Firebase Authentication</h3>
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
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Firebase UID</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                    <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($firebaseUsers['data'] as $fbUser)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-2.5 px-3 font-mono text-xs text-gray-500">{{ $fbUser['uid'] }}</td>
                    <td class="py-2.5 px-3 text-gray-700">{{ $fbUser['email'] }}</td>
                    <td class="py-2.5 px-3">
                        @if($fbUser['emailVerified'])
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            <i class="fas fa-check-circle text-xs"></i> Terverifikasi
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                            <i class="fas fa-clock text-xs"></i> Belum Terverifikasi
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
