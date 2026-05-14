@extends('layouts.admin')

@section('title', 'Manajemen Kelas')
@section('page-title', 'Manajemen Kelas')

@section('content')

{{-- Header --}}
<div class="mb-8">
    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Daftar Kelas</h1>
    <p class="text-slate-500 mt-1 text-sm font-medium">Lihat dan pantau seluruh kelas yang aktif di platform Q-Les.</p>
</div>

{{-- Search Bar --}}
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 mb-8">
    <form method="GET" action="{{ route('admin.classes') }}" class="flex gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari nama kelas atau kode kelas..."
                class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50/50"
            >
        </div>
        <button type="submit"
            class="px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-100">
            Cari Kelas
        </button>
    </form>
</div>

{{-- Classes Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($classes as $class)
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-wider rounded-lg border border-slate-200">
                {{ $class->code }}
            </span>
        </div>

        <h3 class="text-lg font-bold text-slate-800 mb-1 truncate">{{ $class->name }}</h3>
        <p class="text-sm text-slate-500 mb-4 flex items-center gap-2">
            <i class="fas fa-user-tie text-xs text-slate-400"></i>
            {{ $class->teacher->name ?? 'Unknown Teacher' }}
        </p>

        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Siswa</p>
                <p class="text-lg font-black text-slate-800">{{ $class->members->count() }}</p>
            </div>
            <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Dibuat</p>
                <p class="text-xs font-bold text-slate-800 mt-1">{{ $class->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button class="flex-1 py-2.5 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-900 transition-colors">
                Detail Kelas
            </button>
            <button class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-xl text-slate-400 hover:text-rose-500 hover:border-rose-100 hover:bg-rose-50 transition-all">
                <i class="fas fa-trash-alt text-sm"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 bg-white rounded-3xl border border-dashed border-slate-200 flex flex-col items-center justify-center">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
            <i class="fas fa-school text-4xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Tidak ada kelas ditemukan</h3>
        <p class="text-sm text-slate-500">Gunakan kata kunci pencarian lain atau buat kelas baru melalui aplikasi.</p>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-8">
    {{ $classes->links() }}
</div>

@endsection
