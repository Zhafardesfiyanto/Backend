@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Welcome Header --}}
<div class="mb-8">
    <h1 class="text-3xl font-black text-slate-800 tracking-tight">System Overview</h1>
    <p class="text-slate-500 mt-1 text-sm font-medium">Real-time update on platform metrics and recent activities.</p>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Stat: Users -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-shadow">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-50 to-blue-100 rounded-bl-full -mr-4 -mt-4 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Total Pengguna</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalUsers ?? 0 }}</h3>
                <p class="text-xs text-green-500 mt-2 font-medium flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    +12% vs last month
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Stat: Orders/Submissions -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-shadow">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-bl-full -mr-4 -mt-4 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Total Submission</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalSubmissions ?? 0 }}</h3>
                <p class="text-xs text-green-500 mt-2 font-medium flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    +5% vs last month
                </p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500">
                <i class="fas fa-shopping-cart text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Stat: Revenue -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-shadow">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-50 to-purple-100 rounded-bl-full -mr-4 -mt-4 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Total Kelas</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalClasses ?? 0 }}</h3>
                <p class="text-xs text-green-500 mt-2 font-medium flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    +18% vs last month
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500">
                <i class="fas fa-school text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Stat: Products -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-shadow">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-orange-50 to-orange-100 rounded-bl-full -mr-4 -mt-4 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-slate-500 mb-1">Total Ujian</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalExams ?? 0 }}</h3>
                <p class="text-xs text-rose-500 mt-2 font-medium flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    -2% vs last month
                </p>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500">
                <i class="fas fa-file-alt text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Revenue Line Chart --}}
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-slate-800">Tren Submission (6 Bulan)</h3>
            <button class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Category Donut Chart --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-slate-800">Distribusi Pengguna</h3>
        </div>
        <div class="relative flex-1 flex items-center justify-center h-64">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

{{-- Orders Table + New Users --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Orders Table --}}
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 overflow-hidden">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-slate-800">Submission Terbaru</h3>
            <a href="#" class="text-sm text-indigo-600 font-semibold hover:text-indigo-700">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 pt-2 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">ID</th>
                        <th class="pb-3 pt-2 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Pengguna</th>
                        <th class="pb-3 pt-2 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="pb-3 pt-2 px-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentSubmissions as $submission)
                    <tr class="hover:bg-slate-50 transition-colors group cursor-pointer">
                        <td class="py-4 px-4">
                            <span class="text-slate-500 font-mono text-xs bg-slate-100 px-2 py-1 rounded-md">#{{ $submission->id }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($submission->student->name ?? 'User') }}&background=e0e7ff&color=4f46e5&size=32" class="w-8 h-8 rounded-full">
                                <span class="font-semibold text-slate-800">{{ $submission->student->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            @php
                                $statusColor = match($submission->status ?? '') {
                                    'completed'   => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'on-progress' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'canceled'    => 'bg-rose-100 text-rose-700 border-rose-200',
                                    default       => 'bg-slate-100 text-slate-600 border-slate-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                                {{ ucfirst($submission->status ?? 'Unknown') }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-slate-500 font-medium">
                            {{ $submission->created_at?->format('d M Y') ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-slate-400 font-medium">Belum ada data submission.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- New Users List --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Pengguna Baru</h3>
        <div class="space-y-4">
            @forelse($newUsers as $user)
            <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 cursor-pointer">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=f1f5f9&color=64748b&size=48"
                    class="w-12 h-12 rounded-full flex-shrink-0 shadow-sm"
                    alt="{{ $user->name }}">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-slate-800 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-slate-500 truncate mt-0.5">{{ $user->email }}</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                    <i class="fas fa-chevron-right text-xs"></i>
                </div>
            </div>
            @empty
            <div class="py-8 text-center">
                <p class="text-sm text-slate-400 font-medium">Belum ada pengguna terdaftar.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
            <button class="w-full py-2.5 bg-indigo-50 text-indigo-600 font-semibold rounded-xl hover:bg-indigo-100 transition-colors text-sm">
                Kelola Semua Pengguna
            </button>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Revenue Line Chart Customization
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    
    // Create gradient
    const gradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']),
            datasets: [{
                label: 'Submission',
                data: @json($revenueData['values'] ?? [12, 19, 15, 25, 22, 30]),
                borderColor: '#4f46e5',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, family: 'sans-serif' },
                    bodyFont: { size: 13, family: 'sans-serif' },
                    displayColors: false,
                    cornerRadius: 8,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: '#94a3b8' },
                    border: { display: false },
                    grid: { color: '#f1f5f9', drawBorder: false }
                },
                x: { 
                    ticks: { color: '#94a3b8' },
                    border: { display: false },
                    grid: { display: false } 
                }
            }
        }
    });

    // Category Donut Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: @json($categoryData['labels'] ?? ['Siswa', 'Guru', 'Umum']),
            datasets: [{
                data: @json($categoryData['values'] ?? [60, 25, 15]),
                backgroundColor: ['#4f46e5', '#38bdf8', '#fbbf24', '#f43f5e', '#a855f7'],
                borderWidth: 4,
                borderColor: '#ffffff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { 
                        padding: 20, 
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 12, family: 'sans-serif', weight: '500' },
                        color: '#64748b'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 8,
                }
            },
            cutout: '75%',
        }
    });
</script>
@endpush
