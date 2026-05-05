<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Center | Q-Les HQ</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --bg-main: #0f172a; --bg-card: rgba(30, 41, 59, 0.7); --accent: #38bdf8; --glass-border: rgba(255, 255, 255, 0.1); }
        body { background-color: var(--bg-main); color: white; font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: var(--bg-card); backdrop-filter: blur(12px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 2rem; }
        .btn-fix { background: var(--accent); color: var(--bg-main); font-weight: bold; border-radius: 12px; border: none; padding: 10px 20px; transition: 0.3s; }
        .btn-fix:hover { opacity: 0.8; transform: scale(1.05); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="fw-bold"><i class="fas fa-tools text-info me-2"></i> Service Center</h2>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light rounded-pill px-4">Kembali ke HQ</a>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="glass-card h-100">
                    <h5 class="fw-bold"><i class="fas fa-undo me-2 text-warning"></i> Reset Status Ujian</h5>
                    <p class="text-secondary small">Gunakan ini jika siswa mengalami kendala teknis (mati lampu/sinyal) saat ujian berlangsung agar mereka bisa login kembali.</p>
                    <input type="text" class="form-control bg-dark text-white border-secondary mb-3" placeholder="Masukkan Email Siswa...">
                    <button class="btn-fix w-100">RESET SEKARANG</button>
                </div>
            </div>

            <div class="col-md-6">
                <div class="glass-card h-100">
                    <h5 class="fw-bold"><i class="fas fa-unlock me-2 text-success"></i> Unlock Device Akun</h5>
                    <p class="text-secondary small">Jika akun siswa terkunci karena login di terlalu banyak perangkat (Flutter device limit).</p>
                    <input type="text" class="form-control bg-dark text-white border-secondary mb-3" placeholder="Masukkan Email Siswa...">
                    <button class="btn-fix w-100">UNLOCK DEVICE</button>
                </div>
            </div>

            <div class="col-md-12">
                <div class="glass-card">
                    <h5 class="fw-bold"><i class="fas fa-heartbeat me-2 text-danger"></i> System Health Check</h5>
                    <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-dark rounded border border-secondary">
                        <span>Database Connection: <b class="text-success small">ONLINE</b></span>
                        <span>API Server: <b class="text-success small">ACTIVE</b></span>
                        <span>Storage Space: <b class="text-info small">85% FREE</b></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>