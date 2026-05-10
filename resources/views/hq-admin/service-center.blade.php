@extends('layouts.admin')

@section('title', 'Customer Service')
@section('page-title', 'Customer Service')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

{{-- Status Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.service') }}"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                {{ $status === '' ? 'bg-blue-600 text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
            Semua
        </a>
        <a href="{{ route('admin.service', ['status' => 'open']) }}"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                {{ $status === 'open' ? 'bg-blue-600 text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
            <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>Open
        </a>
        <a href="{{ route('admin.service', ['status' => 'in_progress']) }}"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                {{ $status === 'in_progress' ? 'bg-blue-600 text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
            <span class="inline-block w-2 h-2 rounded-full bg-yellow-500 mr-1.5"></span>In Progress
        </a>
        <a href="{{ route('admin.service', ['status' => 'closed']) }}"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                {{ $status === 'closed' ? 'bg-blue-600 text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
            <span class="inline-block w-2 h-2 rounded-full bg-gray-400 mr-1.5"></span>Closed
        </a>
    </div>
</div>

{{-- Tickets Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-gray-800">Daftar Tiket</h3>
        <span class="text-xs text-gray-500">{{ count($tickets) }} tiket</span>
    </div>

    @forelse($tickets as $ticket)
    <div class="border border-gray-100 rounded-xl mb-3 overflow-hidden" x-data="{ open: false }">
        {{-- Ticket Row --}}
        <div
            class="flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition-colors"
            @click="open = !open">
            <div class="flex-shrink-0 text-gray-400">
                <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
            </div>
            <div class="flex-shrink-0 w-8 text-center">
                <span class="text-xs font-mono text-gray-400">UID</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $ticket->subject }}</p>
                <p class="text-xs text-gray-500">{{ $ticket->user->name ?? 'Anonim' }}</p>
            </div>
            <div class="flex-shrink-0">
                @php
                    $statusConfig = match($ticket->status) {
                        'open'        => ['color' => 'bg-green-100 text-green-700', 'label' => 'Open'],
                        'in_progress' => ['color' => 'bg-yellow-100 text-yellow-700', 'label' => 'In Progress'],
                        'closed'      => ['color' => 'bg-gray-100 text-gray-600', 'label' => 'Closed'],
                        default       => ['color' => 'bg-gray-100 text-gray-600', 'label' => $ticket->status],
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusConfig['color'] }}">
                    {{ $statusConfig['label'] }}
                </span>
            </div>
            <div class="flex-shrink-0 text-xs text-gray-400">
                {{ $ticket->created_at?->format('d M Y') ?? '-' }}
            </div>
        </div>

        {{-- Ticket Detail + Reply Form --}}
        <div x-show="open" x-transition class="border-t border-gray-100 bg-gray-50 p-5">
            {{-- Message --}}
            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Pesan dari Pengguna</p>
                <div class="bg-white border border-gray-200 rounded-lg p-4 text-sm text-gray-700 leading-relaxed">
                    {{ $ticket->message }}
                </div>
            </div>

            {{-- Pesan di Firestore langsung gabung jadi thread --}}

            {{-- Update Form --}}
            <form method="POST" action="{{ route('admin.service.update', $ticket->id) }}">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                            Status
                        </label>
                        <select name="status"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="open"        {{ $ticket->status === 'open'        ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="closed"      {{ $ticket->status === 'closed'      ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                            Balasan Admin
                        </label>
                        <textarea
                            name="admin_reply"
                            rows="3"
                            maxlength="2000"
                            placeholder="Tulis balasan untuk pengguna..."
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-1.5"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @empty
    <div class="py-12 text-center">
        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-400 text-sm">Tidak ada tiket saat ini.</p>
    </div>
    @endforelse


</div>

@endsection
