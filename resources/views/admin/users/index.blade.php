{{-- Asumsi kamu punya layout utama --}}

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">User Management (God Mode)</h2>
        <button class="btn btn-danger" onclick="return confirm('RESET SEMUA SESI?')">Emergency Reset</button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Firebase UID</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-info">{{ strtoupper($user->role) }}</span></td>
                        <td><small class="text-muted">{{ $user->firebase_uid ?? '-' }}</small></td>
                        <td>
                            <form action="{{ url('hq-admin/users/'.$user->id) }}" method="POST" onsubmit="return confirm('Hapus User ini permanen?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Destroy</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }} {{-- Untuk pagination --}}
        </div>
    </div>
</div>
