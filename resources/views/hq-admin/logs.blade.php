<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs | Q-Les HQ</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --bg-main: #0f172a; --bg-card: rgba(30, 41, 59, 0.7); --accent: #38bdf8; --glass-border: rgba(255, 255, 255, 0.1); }
        body { background-color: var(--bg-main); color: white; font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; }
        .glass-card { background: var(--bg-card); backdrop-filter: blur(12px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 2rem; }
        .log-table { color: #f8fafc; --bs-table-bg: transparent; }
        .log-table thead th { color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--glass-border); }
        .log-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s; }
        .log-table tbody tr:hover { background: rgba(255,255,255,0.02); }
        .text-info-custom { color: var(--accent); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold m-0"><i class="fas fa-history text-info me-2"></i> Audit Logs</h2>
                <p class="text-secondary small m-0">Rekam jejak seluruh aktivitas sistem Q-Les.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light rounded-pill px-4">Kembali ke HQ</a>
        </div>

        <div class="glass-card shadow-lg">
            <div class="table-responsive">
                <table class="table log-table align-middle">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aktivitas</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="small text-secondary">{{ $log->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <span class="fw-bold text-info-custom">{{ $log->user->name ?? 'System' }}</span>
                                <div class="small text-secondary">{{ $log->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-dark border border-secondary text-light px-3 py-2">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="small text-secondary font-monospace">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-secondary">Belum ada log aktivitas hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{-- Gunakan pagination jika data banyak --}}
                @if(method_exists($logs, 'links'))
                    {{ $logs->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>
    </div>
</body>
</html>