<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Submission; // 1. TAMBAHKAN INI (Tadi belum ada)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB; // 2. TAMBAHKAN INI untuk query manual

class SuperAdminController extends Controller
{
    public function index()
    {
        // Ambil data database
        $users = User::latest()->take(5)->get(); // Kita butuh $users untuk tabel di dashboard
        $logs = AuditLog::with('user')->latest()->take(5)->get(); // Kita butuh $logs untuk activity feed

        // Inisialisasi default apiData agar view tidak error
        $apiData = [
            'active_users' => 0,
            'total_hits' => 0,
        ];

        try {
            // Simulasi nembak API Q-Les Cloud
            $apiResponse = Http::timeout(3)->get('https://api.test/v1/stats');
            
            if ($apiResponse->successful()) {
                // Pastikan struktur JSON dari API sesuai dengan variabel di view ($apiData)
                $apiData = $apiResponse->json();
            }
        } catch (\Exception $e) {
            $apiData['error'] = 'Offline'; 
        }

        // 3. Kirim ke View (Pastikan nama variabel sama dengan yang ada di Blade)
        return view('hq-admin.dashboard', compact('users', 'logs', 'apiData'));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('hq-admin.users', compact('users')); 
    }

    public function logs()
    {
        $logs = AuditLog::with('user')->latest()->paginate(20);
        return view('hq-admin.logs', compact('logs')); 
    }

    public function serviceCenter()
    {
        return view('hq-admin.service-center');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        AuditLog::record(
            auth()->id(),
            'DELETE_USER',
            'users',
            $user->id,
            "Menghapus akun permanen: {$user->email} ({$user->role})"
        );

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    public function emergencyReset()
    {
        // Menggunakan DB agar lebih cepat untuk masal
        DB::table('submissions')->where('status', 'on-progress')->update(['status' => 'canceled']);

        AuditLog::record(auth()->id(), 'SYSTEM_RESET', 'submissions', null, 'Melakukan reset masal pada ujian aktif');

        return back()->with('info', 'Sistem berhasil di-reset!');
    }

    public function resetExamSession($userId)
    {
        $submission = Submission::where('user_id', $userId)->where('status', 'on-progress')->first();
        
        if ($submission) {
            $submission->update(['status' => 'canceled']);

            AuditLog::record(
                auth()->id(),
                'SERVICE_RESET',
                'submissions',
                $submission->id,
                "Admin mereset sesi ujian User ID: $userId karena kendala teknis"
            );

            return back()->with('success', 'Sesi ujian berhasil di-reset!');
        }
        return back()->with('error', 'Tidak ada sesi aktif ditemukan.');
    }
}   