@extends('layouts.admin')

@section('title', 'Rating Pengguna')
@section('page-title', 'Rating & Ulasan Pengguna')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

    {{-- Rata-rata Rating --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-star text-amber-500 text-lg"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Rata-rata Rating</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($avgRating, 1) }} <span class="text-sm font-normal text-gray-400">/ 5.0</span></p>
        </div>
    </div>

    {{-- Total Penilaian --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-users text-purple-500 text-lg"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Penilaian</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalCount }}</p>
        </div>
    </div>

    {{-- Bintang 5 --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-thumbs-up text-green-500 text-lg"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Rating 5 Bintang</p>
            <p class="text-2xl font-bold text-gray-800">{{ $distribution[5] ?? 0 }}
                <span class="text-sm font-normal text-gray-400">pengguna</span>
            </p>
        </div>
    </div>

</div>

{{-- Charts & Distribution --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Distribusi Bintang --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Distribusi Bintang</h3>
        <div class="space-y-3">
            @foreach(array_reverse(range(1, 5), true) as $star)
            @php
                $count = $distribution[$star] ?? 0;
                $pct   = $totalCount > 0 ? round(($count / $totalCount) * 100) : 0;
                $color = match($star) {
                    5 => 'bg-green-500',
                    4 => 'bg-lime-400',
                    3 => 'bg-amber-400',
                    2 => 'bg-orange-400',
                    1 => 'bg-red-500',
                };
            @endphp
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-500 w-4 text-right">{{ $star }}</span>
                <i class="fas fa-star text-amber-400 text-xs"></i>
                <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="{{ $color }} h-2.5 rounded-full transition-all duration-500"
                         style="width: {{ $pct }}%"></div>
                </div>
                <span class="text-xs text-gray-500 w-10 text-right">{{ $count }} ({{ $pct }}%)</span>
            </div>
            @endforeach
        </div>

        {{-- Big star display --}}
        <div class="mt-5 pt-4 border-t border-gray-100 text-center">
            <p class="text-4xl font-bold text-gray-800">{{ number_format($avgRating, 1) }}</p>
            <div class="flex justify-center gap-1 my-2">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($avgRating))
                        <i class="fas fa-star text-amber-400 text-base"></i>
                    @else
                        <i class="far fa-star text-gray-300 text-base"></i>
                    @endif
                @endfor
            </div>
            <p class="text-xs text-gray-400">berdasarkan {{ $totalCount }} ulasan dari Firestore</p>
        </div>
    </div>

    {{-- Tag Cloud --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Tag Terpopuler</h3>
        @php
            $allTags = [];
            foreach ($ratings as $r) {
                foreach (($r['tags'] ?? []) as $tag) {
                    $allTags[$tag] = ($allTags[$tag] ?? 0) + 1;
                }
            }
            arsort($allTags);
        @endphp
        @if(count($allTags) > 0)
        <div class="flex flex-wrap gap-2">
            @foreach($allTags as $tag => $count)
            @php
                $intensity = min(9, max(1, (int)($count / max(array_values($allTags)) * 9)));
                $size = $count > 5 ? 'text-base' : ($count > 2 ? 'text-sm' : 'text-xs');
            @endphp
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-purple-50 text-purple-700 font-medium {{ $size }}">
                {{ $tag }}
                <span class="text-xs bg-purple-200 text-purple-800 rounded-full px-1.5 py-0.5 font-bold">{{ $count }}</span>
            </span>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-400 text-center py-8">Belum ada data tag.</p>
        @endif
    </div>

</div>

{{-- Daftar Ulasan --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-gray-800">Semua Ulasan</h3>
        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
            Sumber: Firestore — <code class="font-mono">app_ratings</code>
        </span>
    </div>

    @if(count($ratings) === 0)
    <div class="text-center py-16 text-gray-400">
        <i class="fas fa-star text-4xl mb-3 text-gray-200"></i>
        <p class="text-sm">Belum ada rating yang masuk.</p>
        <p class="text-xs mt-1">Rating dari pengguna akan muncul di sini secara real-time dari Firestore.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($ratings as $r)
        <div class="border border-gray-100 rounded-xl p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600
                                flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($r['name'], 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $r['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $r['submittedAt'] ?? 'Tanggal tidak tersedia' }}</p>
                    </div>
                </div>
                {{-- Stars --}}
                <div class="flex gap-0.5 flex-shrink-0">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $r['rating'])
                            <i class="fas fa-star text-amber-400 text-xs"></i>
                        @else
                            <i class="far fa-star text-gray-200 text-xs"></i>
                        @endif
                    @endfor
                </div>
            </div>

            {{-- Tags --}}
            @if(!empty($r['tags']))
            <div class="flex flex-wrap gap-1 mt-3">
                @foreach($r['tags'] as $tag)
                <span class="text-xs bg-purple-50 text-purple-600 px-2 py-0.5 rounded-full">{{ $tag }}</span>
                @endforeach
            </div>
            @endif

            {{-- Review text --}}
            @if(!empty($r['review']))
            <p class="text-sm text-gray-600 mt-3 leading-relaxed italic">"{{ $r['review'] }}"</p>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
