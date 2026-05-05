

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold mb-4">🛠️ Q-Les Service Center</h3>
            
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ url('/hq-admin/users') }}" method="GET" class="row g-3">
                        <div class="col-md-10">
                            <input type="text" name="search" class="form-control" placeholder="Cari Email, Nama, atau Firebase UID Siswa/Guru...">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Cari User</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Daftar Akun Terdeteksi</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>User Info</th>
                                    <th>Role</th>
                                    <th>Status Ujian</th>
                                    <th>Aksi Service Center</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                                    <td>
                                        @if($user->is_exam_active) 
                                            <span class="badge bg-warning text-dark">Sedang Ujian</span>
                                        @else
                                            <span class="text-muted">Idle</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-warning" onclick="resetExam({{ $user->id }})">Reset Session</button>
                                        
                                        <button class="btn btn-sm btn-outline-info" onclick="changeRole({{ $user->id }})">Ubah Role</button>
                                        
                                        <form action="{{ url('hq-admin/users/'.$user->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus Permanen Akun Ini?')">Banned</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
