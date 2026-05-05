<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HQ Command Center | Premium Q-Les</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-main: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.7);
            --accent: #38bdf8;
            --text-dim: #94a3b8;
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--bg-main);
            color: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: var(--accent);
            transform: translateY(-5px);
        }

        .sidebar {
            height: 100vh;
            background: rgba(15, 23, 42, 0.9);
            border-right: 1px solid var(--glass-border);
            padding: 2rem 1.5rem;
            position: sticky;
            top: 0;
        }

        .nav-link {
            color: var(--text-dim);
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: 0.3s;
            text-decoration: none;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent);
        }

        .stat-card { padding: 1.5rem; text-align: center; }

        .table {
            color: white;
            --bs-table-bg: transparent;
            --bs-table-hover-bg: rgba(255, 255, 255, 0.03);
        }

        .table thead th {
            color: var(--text-dim);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--glass-border);
        }

        .badge-admin {
            background: rgba(56, 189, 248, 0.2);
            color: var(--accent);
            border: 1px solid var(--accent);
            font-size: 0.7rem;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
        }

        .btn-action {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 10px;
            width: 35px; height: 35px;
            display: inline-flex; align-items: center; justify-content: center;
        }

        .btn-action:hover { background: var(--accent); color: var(--bg-main); }

        .audit-item { padding: 1rem; border-bottom: 1px solid var(--glass-border); transition: 0.2s; }
        .audit-item:hover { background: rgba(255, 255, 255, 0.02); }
        .text-accent { color: var(--accent); }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar d-none d-md-block">
                <h4 class="fw-bold mb-5"><i class="fas fa-shield-alt text-info me-2"></i> Q-LES HQ</h4>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-th-large me-2"></i> Overview
                    </a>
                    <a class="nav-link" href="#"><i class="fas fa-users me-2"></i> Users</a>
                    <a class="nav-link" href="#"><i class="fas fa-server me-2"></i> Services</a>
                    <a class="nav-link" href="#"><i class="fas fa-file-invoice me-2"></i> Audit Logs</a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="mt-5">
                        @csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>

            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold m-0 text-white">Command Center</h2>
                        <p class="text-dim small">Monitor and manage your system ecosystem.</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="text-end me-3">
                            <p class="m-0 fw-bold">{{ auth()->user()->name }}</p>
                            <span class="badge-admin">SUPER ADMIN</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=38bdf8&color=fff" class="rounded-circle border border-info" width="45">
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="glass-card p-4 text-center border-{{ isset($apiData['error']) ? 'danger' : 'info' }}">
                            <p class="text-dim small mb-1">Cloud Server Status</p>
                            <h4 class="fw-bold {{ isset($apiData['error']) ? 'text-danger' : 'text-info' }}">
                                <i class="fas fa-signal me-2"></i>
                                {{ isset($apiData['error']) ? 'OFFLINE' : 'ONLINE' }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4 text-center">
                            <p class="text-dim small mb-1">Active Flutter Sessions</p>
                            <h4 class="fw-bold text-accent">
                                {{ $apiData['active_users'] ?? '0' }} <span class="small text-dim">Siswa</span>
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4 text-center">
                            <p class="text-dim small mb-1">API Requests Today</p>
                            <h4 class="fw-bold text-white">
                                {{ number_format($apiData['total_hits'] ?? 0) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="glass-card stat-card">
                            <p class="text-dim small mb-1">Total Database Users</p>
                            <h3 class="fw-bold m-0 text-white">{{ $users->count() }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="glass-card stat-card">
                            <p class="text-dim small mb-1">Database Status</p>
                            <h3 class="fw-bold m-0 text-success">CONNECTED</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-4">User Management</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>USER</th>
                                            <th>ROLE</th>
                                            <th class="text-end">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $user->name }}</div>
                                                <div class="text-dim small">{{ $user->email }}</div>
                                            </td>
                                            <td><span class="badge-admin">{{ strtoupper($user->role) }}</span></td>
                                            <td class="text-end">
                                                <button class="btn-action me-1"><i class="fas fa-pen small"></i></button>
                                                <button class="btn-action text-danger"><i class="fas fa-trash small"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="glass-card p-4 h-100">
                            <h5 class="fw-bold mb-4">System Activity</h5>
                            @forelse($logs as $log)
                                <div class="audit-item">
                                    <p class="m-0 small fw-bold text-info">{{ $log->user->name }}</p>
                                    <p class="m-0 text-dim small">{{ $log->action }}</p>
                                    <span class="text-muted" style="font-size: 0.6rem;">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="text-center text-dim mt-5">No logs recorded.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>