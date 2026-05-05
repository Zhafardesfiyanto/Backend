
<div class="container-fluid">
    <h2 class="fw-bold mb-4 text-secondary">System Audit Logs</h2>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach($logs as $log)
                <li class="list-group-item d-flex justify-content-between align-items-start py-3">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold text-primary">{{ $log->action }}</div>
                        <span class="small text-muted">{{ $log->description }}</span>
                        <br>
                        <small class="badge bg-light text-dark border mt-1">IP: {{ $log->ip_address }}</small>
                    </div>
                    <div class="text-end">
                        <div class="small fw-bold">{{ $log->user->name }}</div>
                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
