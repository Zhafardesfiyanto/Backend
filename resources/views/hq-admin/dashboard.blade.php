@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
    <x-admin.stats-card
        label="Total Pengguna"
        :value="$totalUsers ?? 0"
        icon="fas fa-users"
        color="blue"
    />
    <x-admin.stats-card
        label="Total Pesanan"
        :value="$totalSubmissions ?? 0"
        icon="fas fa-shopping-cart"
        color="green"
    />
    <x-admin.stats-card
        label="Total Pendapatan"
        :value="$totalRevenue ?? 0"
        icon="fas fa-dollar-sign"
        color="purple"
    />
    <x-admin.stats-card
        label="Produk Terjual"
        :value="$totalProducts ?? 0"
        icon="fas fa-box"
        color="orange"
    />
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    {{-- Revenue Line Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Tren Submission (6 Bulan Terakhir)</h3>
        <div class="relative h-56">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Category Donut Chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Distribusi Pengguna</h3>
        <div class="relative h-56 flex items-center justify-center">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

{{-- Orders Table + New Users --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Orders Table --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Submission Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">ID</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Pengguna</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentSubmissions as $submission)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-2.5 px-3 text-gray-500 font-mono text-xs">#{{ $submission->id }}</td>
                        <td class="py-2.5 px-3 font-medium text-gray-800">
                            {{ $submission->student->name ?? 'N/A' }}
                        </td>
                        <td class="py-2.5 px-3">
                            @php
                                $statusColor = match($submission->status ?? '') {
                                    'completed'   => 'bg-green-100 text-green-700',
                                    'on-progress' => 'bg-yellow-100 text-yellow-700',
                                    'canceled'    => 'bg-red-100 text-red-700',
                                    default       => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ $submission->status ?? '-' }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-gray-500 text-xs">
                            {{ $submission->created_at?->format('d M Y') ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400 text-sm">
                            Belum ada data submission.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- New Users List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Pengguna Baru</h3>
        <div class="space-y-3">
            @forelse($newUsers as $user)
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=3b82f6&color=fff&size=36"
                    class="w-9 h-9 rounded-full flex-shrink-0"
                    alt="{{ $user->name }}">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada pengguna terdaftar.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Revenue Line Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueData['labels'] ?? []),
            datasets: [{
                label: 'Submission',
                data: @json($revenueData['values'] ?? []),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.08)',
                borderWidth: 2,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 4,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Category Donut Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: @json($categoryData['labels'] ?? []),
            datasets: [{
                data: @json($categoryData['values'] ?? []),
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                borderWidth: 0,
                hoverOffset: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 12, font: { size: 11 } }
                }
            },
            cutout: '65%',
        }
    });
</script>
@endpush
