@extends('layouts.admin')

@section('title', 'Customer Service')
@section('page-title', 'Customer Service')

@section('content')

{{-- Welcome Header --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pusat Layanan Pelanggan</h1>
        <p class="text-slate-500 mt-1 text-sm font-medium">Tanggapi laporan, keluhan, dan tiket bantuan dari pengguna dengan cepat.</p>
    </div>
    <div class="hidden sm:block">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
            Real-time Sync
        </span>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl shadow-sm" x-data="{ show: true }" x-show="show">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
        <i class="fas fa-check text-emerald-600"></i>
    </div>
    <span class="font-medium flex-1">{{ session('success') }}</span>
    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

{{-- Status Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-2 mb-6 inline-flex flex-wrap gap-1">
    <a href="{{ route('admin.service') }}"
        class="px-5 py-2 text-sm font-bold rounded-xl transition-all duration-200
            {{ $status === '' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
        Semua Tiket
    </a>
    <a href="{{ route('admin.service', ['status' => 'open']) }}"
        class="px-5 py-2 text-sm font-bold rounded-xl transition-all duration-200 flex items-center
            {{ $status === 'open' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
        <span class="inline-block w-2 h-2 rounded-full {{ $status === 'open' ? 'bg-white' : 'bg-emerald-500' }} mr-2"></span>Open
    </a>
    <a href="{{ route('admin.service', ['status' => 'in_progress']) }}"
        class="px-5 py-2 text-sm font-bold rounded-xl transition-all duration-200 flex items-center
            {{ $status === 'in_progress' ? 'bg-amber-500 text-white shadow-md shadow-amber-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
        <span class="inline-block w-2 h-2 rounded-full {{ $status === 'in_progress' ? 'bg-white' : 'bg-amber-500' }} mr-2"></span>In Progress
    </a>
    <a href="{{ route('admin.service', ['status' => 'closed']) }}"
        class="px-5 py-2 text-sm font-bold rounded-xl transition-all duration-200 flex items-center
            {{ $status === 'closed' ? 'bg-slate-600 text-white shadow-md shadow-slate-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
        <span class="inline-block w-2 h-2 rounded-full {{ $status === 'closed' ? 'bg-white' : 'bg-slate-400' }} mr-2"></span>Closed
    </a>
</div>

{{-- Tickets List --}}
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 overflow-hidden">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-800">Daftar Tiket Dukungan</h3>
        <span class="text-sm font-semibold bg-slate-100 text-slate-500 px-3 py-1 rounded-full">{{ count($tickets) }} Total</span>
    </div>

    <div class="space-y-4">
        @forelse($tickets as $ticket)
        <div class="border border-slate-100 rounded-2xl overflow-hidden bg-white hover:border-indigo-100 transition-colors shadow-sm" x-data="{ open: false }">
            {{-- Ticket Row --}}
            <div
                class="flex items-center gap-4 p-5 cursor-pointer hover:bg-slate-50 transition-colors group"
                @click="open = !open">
                
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                    <i class="fas fa-chevron-down text-sm transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                </div>
                
                <div class="flex-shrink-0 text-center hidden sm:block">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-1 rounded-md">ID #{{ substr(md5($ticket->id), 0, 5) }}</span>
                </div>
                
                <div class="flex-1 min-w-0">
                    <p class="text-base font-bold text-slate-800 truncate mb-1 group-hover:text-indigo-600 transition-colors">{{ $ticket->subject }}</p>
                    <div class="flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name ?? 'Anonim') }}&background=f1f5f9&color=64748b&size=20" class="w-5 h-5 rounded-full">
                        <p class="text-xs font-medium text-slate-500">{{ $ticket->user->name ?? 'Pengguna Anonim' }}</p>
                    </div>
                </div>
                
                <div class="flex-shrink-0">
                    @php
                        $statusConfig = match($ticket->status) {
                            'open'        => ['color' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'label' => 'Open'],
                            'in_progress' => ['color' => 'bg-amber-100 text-amber-700 border-amber-200', 'label' => 'In Progress'],
                            'closed'      => ['color' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => 'Closed'],
                            default       => ['color' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => ucfirst($ticket->status)],
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusConfig['color'] }}">
                        {{ $statusConfig['label'] }}
                    </span>
                </div>
                
                <div class="flex-shrink-0 text-xs font-semibold text-slate-400 hidden md:block">
                    {{ $ticket->created_at?->diffForHumans() ?? '-' }}
                </div>
            </div>

            {{-- Ticket Detail + Reply Form --}}
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="border-t border-slate-100 bg-slate-50/50 p-6" style="display: none;">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Message --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-md bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">
                                <i class="fas fa-comment-alt"></i>
                            </div>
                            <h4 class="text-sm font-bold text-slate-700">Pesan Pengguna</h4>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 text-sm text-slate-600 leading-relaxed shadow-sm relative">
                            <!-- pointer triangle -->
                            <div class="absolute -top-2 left-6 w-4 h-4 bg-white border-t border-l border-slate-200 transform rotate-45"></div>
                            <div class="relative z-10 whitespace-pre-wrap">{{ $ticket->message }}</div>
                        </div>
                    </div>

                    {{-- Update Form --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center text-xs">
                                <i class="fas fa-reply"></i>
                            </div>
                            <h4 class="text-sm font-bold text-slate-700">Tindakan Admin</h4>
                        </div>
                        <form method="POST" action="{{ route('admin.service.update', $ticket->id) }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                                    Balasan Resmi
                                </label>
                                <textarea
                                    name="admin_reply"
                                    rows="3"
                                    maxlength="2000"
                                    placeholder="Tulis balasan atau solusi untuk pengguna..."
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50/50 transition-all resize-none"></textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-4 justify-between">
                                <div class="w-full sm:w-auto flex-1">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                                        Update Status Tiket
                                    </label>
                                    <select name="status"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50/50 cursor-pointer">
                                        <option value="open"        {{ $ticket->status === 'open'        ? 'selected' : '' }}>🟢 Tetap Open</option>
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>🟡 Tandai In Progress</option>
                                        <option value="closed"      {{ $ticket->status === 'closed'      ? 'selected' : '' }}>⚪ Tutup Tiket (Resolved)</option>
                                    </select>
                                </div>
                                <button type="submit"
                                    class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-200 transition-all active:scale-95">
                                    <i class="fas fa-paper-plane mr-2"></i>Kirim & Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="py-16 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50/50">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-slate-300 text-3xl">
                <i class="fas fa-inbox"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-700 mb-1">Tidak ada tiket</h4>
            <p class="text-slate-500 text-sm font-medium text-center max-w-sm">Hore! Semua kendala pelanggan telah ditangani. Kotak masuk saat ini kosong.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
